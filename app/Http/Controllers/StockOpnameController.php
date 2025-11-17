<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockAdjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StockOpnameController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'all');

        $query = StockAdjustment::with(['product', 'user'])
            ->where('type', 'increase');

        switch ($period) {
            case 'week':
                $query->where('created_at', '>=', Carbon::now()->startOfWeek());
                break;
            case 'month':
                $query->where('created_at', '>=', Carbon::now()->startOfMonth());
                break;
            case 'year':
                $query->where('created_at', '>=', Carbon::now()->startOfYear());
                break;
            default:
                // all - no filter
                break;
        }

        $adjustments = $query->latest()->paginate(20);
        $totalIncreases = StockAdjustment::where('type', 'increase')->count();

        $counts = [
            'all' => StockAdjustment::where('type', 'increase')->count(),
            'week' => StockAdjustment::where('type', 'increase')->where('created_at', '>=', Carbon::now()->startOfWeek())->count(),
            'month' => StockAdjustment::where('type', 'increase')->where('created_at', '>=', Carbon::now()->startOfMonth())->count(),
            'year' => StockAdjustment::where('type', 'increase')->where('created_at', '>=', Carbon::now()->startOfYear())->count(),
        ];

        $products = Product::with('category')
            ->active()
            ->inStock()
            ->latest()
            ->paginate(20);

        return view('stockopname.index', compact('products', 'adjustments', 'period', 'counts', 'totalIncreases'));
    }

    public function create()
    {
        $products = Product::with('category')
            ->active()
            ->get();

        return view('stockopname.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'physical_counts' => 'required|array',
            'physical_counts.*.id' => 'required|exists:products,id',
            'physical_counts.*.physical' => 'required|integer|min:0',
            'reason' => 'required|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            foreach ($validated['physical_counts'] as $count) {
                $product = Product::findOrFail($count['id']);
                $systemStock = $product->stock;
                $physicalStock = $count['physical'];
                $variance = $physicalStock - $systemStock;

                if ($variance != 0) {
                    $type = $variance > 0 ? 'increase' : 'decrease';
                    $quantity = abs($variance);

                    StockAdjustment::create([
                        'product_id' => $product->id,
                        'type' => $type,
                        'quantity' => $quantity,
                        'reason' => $validated['reason'],
                        'user_id' => Auth::id(),
                    ]);

                    $product->update(['stock' => $physicalStock]);
                }
            }

            DB::commit();

            return redirect()->route('stockopname.index')
                ->with('success', 'Stock opname berhasil disimpan! Stok telah disesuaikan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function addStock()
    {
        return view('stockopname.add');
    }

    public function searchByBarcode(Request $request)
    {
        $barcode = $request->get('q');
        $id = $request->get('id');

        if ($id) {
            // Fetch by ID for Select2 selection
            $product = Product::with('category')
                ->active()
                ->find($id);
        } else {
            $product = Product::with('category')
                ->active()
                ->where('barcode', $barcode)
                ->first();
        }

        if ($product) {
            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'stock' => $product->stock,
                    'unit' => $product->unit,
                    'sku' => $product->sku,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan'
        ]);
    }

    public function searchByName(Request $request)
    {
        $query = $request->get('q');

        $products = Product::with('category')
            ->active()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('sku', 'like', '%' . $query . '%')
                    ->orWhere('barcode', 'like', '%' . $query . '%');
            })
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'text' => $product->name . ' (SKU: ' . $product->sku . ', Barcode: ' . ($product->barcode ?? 'N/A') . ')',
                    'name' => $product->name,
                    'barcode' => $product->barcode,
                    'stock' => $product->stock,
                    'unit' => $product->unit,
                    'sku' => $product->sku,
                ];
            })
        ]);
    }

    public function storeAddition(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:500',
            'barcode' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $product = Product::findOrFail($validated['product_id']);

            StockAdjustment::create([
                'product_id' => $product->id,
                'type' => 'increase',
                'quantity' => $validated['quantity'],
                'reason' => $validated['reason'] ?? 'Manual stock addition',
                'user_id' => Auth::id(),
            ]);

            $product->increment('stock', $validated['quantity']);

            DB::commit();

            return redirect()->route('stockopname.index')
                ->with('success', 'Stok berhasil ditambahkan untuk ' . $product->name . '!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
