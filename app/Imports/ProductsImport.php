<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockAdjustment;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            // Find or create category
            $category = Category::where('name', $row['kategori'])->first();
            if (!$category) {
                $category = Category::create([
                    'name' => $row['kategori'],
                    'type' => 'product',
                    'is_active' => true,
                ]);
            }
        // Find or create/update product
        $product = Product::where('name', $row['nama_produk'])->first();

        if ($product) {
            // Update existing product
            $oldStock = $product->stock;
            $product->update([
                'category_id' => $category->id,
                'barcode' => $row['barcode'] ?? null,
                'description' => $row['deskripsi'] ?? null,
                'price' => (float) str_replace(['Rp', '.', ','], '', $row['harga']),
                'stock' => (int) $row['stok'],
                'min_stock' => (int) ($row['stok_minimum'] ?? 0),
                'unit' => $row['satuan'],
                'slug' => Str::slug($row['nama_produk']),
                'is_active' => true,
            ]);

            // Adjust stock if changed
            $stockDiff = $product->stock - $oldStock;
            if ($stockDiff != 0) {
                StockAdjustment::create([
                    'product_id' => $product->id,
                    'type' => $stockDiff > 0 ? 'increase' : 'decrease',
                    'quantity' => abs($stockDiff),
                    'reason' => 'Stock update from import: ' . $product->name,
                    'user_id' => auth()->id(),
                ]);
            }
        } else {
            // Create new product
            $product = Product::create([
                'category_id' => $category->id,
                'name' => $row['nama_produk'],
                'barcode' => $row['barcode'] ?? null,
                'description' => $row['deskripsi'] ?? null,
                'price' => (float) str_replace(['Rp', '.', ','], '', $row['harga']),
                'stock' => (int) $row['stok'],
                'min_stock' => (int) ($row['stok_minimum'] ?? 0),
                'unit' => $row['satuan'],
                'slug' => Str::slug($row['nama_produk']),
                'is_active' => true,
            ]);

            // Create initial stock adjustment if stock > 0
            if ($product->stock > 0) {
                StockAdjustment::create([
                    'product_id' => $product->id,
                    'type' => 'increase',
                    'quantity' => $product->stock,
                    'reason' => 'Initial stock for imported product: ' . $product->name,
                    'user_id' => auth()->id(),
                ]);
            }
        }
        }
    }

    public function rules(): array
    {
        return [
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'harga' => 'required',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'barcode' => 'nullable',
            'deskripsi' => 'nullable',
            'stok_minimum' => 'nullable|integer|min:0',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'kategori.required' => 'Kategori wajib diisi.',
            'harga.required' => 'Harga wajib diisi.',
            'stok.required' => 'Stok wajib diisi.',
            'satuan.required' => 'Satuan wajib diisi.',
        ];
    }
}
