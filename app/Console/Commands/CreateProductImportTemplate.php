<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CreateProductImportTemplate extends Command
{
    protected $signature = 'product:import-template';
    protected $description = 'Create a product import template file';

    public function handle()
    {
        $templatePath = 'templates/product_import_template.csv';

        $headers = [
            'SKU',
            'Nama Produk',
            'Deskripsi',
            'Kategori',
            'Harga',
            'Stok',
            'Gambar URL',
            'Status',
            'Berat (kg)',
            'Dimensi (L x W x H)'
        ];

        $sampleData = [
            ['SKU001', 'Kaos Putih Polos', 'Kaos berkualitas premium', 'Pakaian', '50000', '100', 'https://example.com/image1.jpg', 'aktif', '0.2', '30x20x5'],
            ['SKU002', 'Celana Jeans Biru', 'Celana jeans standar', 'Pakaian', '150000', '50', 'https://example.com/image2.jpg', 'aktif', '0.5', '35x25x8'],
            ['SKU003', 'Topi Baseball', 'Topi olahraga model terbaru', 'Aksesoris', '80000', '75', 'https://example.com/image3.jpg', 'aktif', '0.15', '30x30x15'],
        ];

        $csvContent = $this->generateCsv($headers, $sampleData);

        Storage::put($templatePath, $csvContent);

        $this->info("Template berhasil dibuat: storage/app/{$templatePath}");
        $this->line("Download dan gunakan template ini untuk import produk.");
    }

    private function generateCsv($headers, $data)
    {
        $output = fopen('php://memory', 'w');

        // Write headers
        fputcsv($output, $headers);

        // Write sample data
        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }
}
