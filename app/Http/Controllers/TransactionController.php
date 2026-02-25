<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'items.item']);
        // Filter by date range
        if (!empty($request->start_date) && !empty($request->end_date)) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Search by invoice number
        if ($request->has('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%');
        }

        $transactions = $query->latest()->paginate(15);

        // Calculate statistics
        $totalRevenue = Transaction::completed()->sum('total');
        $todayRevenue = Transaction::today()->completed()->sum('total');
        $monthRevenue = Transaction::thisMonth()->completed()->sum('total');
        $totalTransactions = Transaction::completed()->count();

        return view('transactions.index', compact(
            'transactions',
            'totalRevenue',
            'todayRevenue',
            'monthRevenue',
            'totalTransactions'
        ));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'items.item']);
        return view('transactions.show', compact('transaction'));
    }

    public function print(Transaction $transaction)
    {
        $transaction->load(['user', 'items.item']);
        return view('transactions.print', compact('transaction'));
    }

    public function export(Request $request)
    {
        $query = Transaction::with(['user', 'items.item']);

        // Apply same filters as index
        if (!empty($request->start_date) && !empty($request->end_date)) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->has('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%');
        }

        $transactions = $query->latest()->get();

        // Generate CSV
        $filename = 'transactions_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Invoice',
                'Kasir',
                'Item Name',
                'Quantity',
                'Price',
                'Subtotal',
                'Total',
                'Paid',
                'Payment Method',
                'Date'
            ]);

            // CSV data
            foreach ($transactions as $transaction) {
                foreach ($transaction->items as $item) {
                    fputcsv($file, [
                        $transaction->invoice_number,
                        $transaction->user->name,
                        $item->item->name,
                        $item->quantity,
                        $item->price,
                        $item->subtotal,
                        $transaction->total,
                        $transaction->paid,
                        ucfirst($transaction->payment_method),
                        $transaction->created_at->format('d/m/Y H:i')
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function pdf(Request $request)
    {
        $query = Transaction::with(['user', 'items.item']);

        // Apply same filters as index
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->has('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%');
        }

        $transactions = $query->latest()->get();

        // Calculate statistics (global for now, can be filtered if needed)
        $totalRevenue = Transaction::completed()->sum('total');
        $todayRevenue = Transaction::today()->completed()->sum('total');
        $monthRevenue = Transaction::thisMonth()->completed()->sum('total');
        $totalTransactions = Transaction::completed()->count();

        $pdf = Pdf::loadView('transactions.pdf', compact(
            'transactions',
            'totalRevenue',
            'todayRevenue',
            'monthRevenue',
            'totalTransactions',
            'request'
        ));

        $filename = 'laporan-transaksi_' . now()->format('Y-m-d_H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

    public function payDebt(Request $request, Transaction $transaction)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $transaction->total,
        ]);

        // Ensure the transaction is a debit and not already completed
        if ($transaction->payment_method !== 'debit' || $transaction->status === 'completed') {
            return response()->json(['success' => false, 'message' => 'Transaksi tidak valid untuk pembayaran hutang.'], 400);
        }

        $amount = $request->amount;

        // If paying full amount, mark as completed
        if ($amount >= $transaction->total) {
            $transaction->update([
                'payment_method' => 'cash',
                'paid' => $transaction->total,
                'change' => 0,
                'status' => 'completed',
            ]);
            $message = 'Hutang berhasil dibayar lunas.';
        } else {
            // Partial payment - keep as debit but update paid amount
            $transaction->update([
                'paid' => $transaction->paid + $amount,
            ]);
            $message = 'Pembayaran sebagian berhasil. Sisa hutang: Rp ' . number_format($transaction->total - $transaction->paid, 0, ',', '.');
        }

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function destroy(Transaction $transaction)
    {
        // Only allow deletion of pending or cancelled transactions
        if ($transaction->status === 'completed') {
            return back()->with('error', 'Transaksi yang sudah selesai tidak dapat dihapus!');
        }

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus!');
    }
}
