<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashierController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->active()->inStock()->get();
        $services = Service::with('category')->active()->get();

        return view('cashier.index', compact('products', 'services'));
    }

    public function search(Request $request)
    {
        $search = $request->get('q');

        $products = Product::with('category')
            ->active()
            ->inStock()
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get();

        $services = Service::with('category')
            ->active()
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get();

        return response()->json([
            'products' => $products,
            'services' => $services
        ]);
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|in:product,service',
            'items.*.id' => 'required|integer',
            'items.*.name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'paid' => 'required|numeric|min:0',
            'change' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,transfer',
        ]);

        DB::beginTransaction();

        try {
            // Create transaction
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'subtotal' => $validated['subtotal'],
                'tax' => $validated['tax'] ?? 0,
                'discount' => $validated['discount'] ?? 0,
                'total' => $validated['total'],
                'paid' => $validated['paid'],
                'change' => $validated['change'],
                'payment_method' => $validated['payment_method'],
                'status' => 'completed',
            ]);

            // Create transaction items
            foreach ($validated['items'] as $item) {
                if ($item['type'] === 'product') {
                    $product = Product::findOrFail($item['id']);

                    // Check stock
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stok {$product->name} tidak mencukupi!");
                    }

                    // Create transaction item
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'item_type' => 'product',
                        'item_id' => $product->id,
                        'item_name' => $item['name'] ?? $product->name,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);

                    // Update stock
                    $product->decrement('stock', $item['quantity']);
                } else {
                    $service = Service::findOrFail($item['id']);

                    // Create transaction item
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'item_type' => 'service',
                        'item_id' => $service->id,
                        'item_name' => $item['name'] ?? $service->name,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil!',
                'transaction_id' => $transaction->id,
                'invoice_number' => $transaction->invoice_number,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
