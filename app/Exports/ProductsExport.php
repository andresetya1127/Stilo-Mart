<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ProductsExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths
{
    protected $query;

    public function __construct(Builder $query = null)
    {
        $this->query = $query ?: Product::with('category');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'nama_produk',
            'kategori',
            'harga',
            'stok',
            'satuan',
            'barcode',
            'deskripsi',
            'stok_minimum',
            'is_active',
        ];
    }

    /**
     * @param mixed $product
     * @return array
     */
    public function map($product): array
    {
        return [
            $product->name,
            $product->category->name,
            $product->price,
            $product->stock,
            $product->unit,
            (string) ($product->barcode ?? ''),
            $product->description ?? '',
            $product->min_stock ?? 0,
            $product->is_active ? 1 : 0,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 45,
            'B' => 25,
            'F'=> 25,
            'G'=> 40,
        ];
    }
}
