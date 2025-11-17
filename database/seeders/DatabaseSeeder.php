<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin StiloMart',
            'email' => 'admin@stilomart.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'admin',
        ]);

        // Create kasir user
        User::create([
            'name' => 'Kasir StiloMart',
            'email' => 'kasir@stilomart.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'kasir',
        ]);

        // Create categories for products
        $productCategories = [
            ['name' => 'Elektronik', 'type' => 'product', 'description' => 'Produk elektronik dan gadget', 'is_active' => true],
            ['name' => 'Fashion', 'type' => 'product', 'description' => 'Pakaian dan aksesoris', 'is_active' => true],
            ['name' => 'Makanan & Minuman', 'type' => 'product', 'description' => 'Produk makanan dan minuman', 'is_active' => true],
            ['name' => 'Alat Tulis', 'type' => 'product', 'description' => 'Perlengkapan kantor dan sekolah', 'is_active' => true],
        ];

        foreach ($productCategories as $category) {
            Category::create($category);
        }

        // Create categories for services
        $serviceCategories = [
            ['name' => 'Perawatan', 'type' => 'service', 'description' => 'Layanan perawatan dan kecantikan', 'is_active' => true],
            ['name' => 'Perbaikan', 'type' => 'service', 'description' => 'Layanan perbaikan dan maintenance', 'is_active' => true],
            ['name' => 'Konsultasi', 'type' => 'service', 'description' => 'Layanan konsultasi profesional', 'is_active' => true],
        ];

        foreach ($serviceCategories as $category) {
            Category::create($category);
        }

        // Create products
        $products = [
            ['category_id' => 1, 'name' => 'Mouse Wireless', 'price' => 150000, 'stock' => 25, 'min_stock' => 5, 'unit' => 'pcs', 'description' => 'Mouse wireless ergonomis dengan baterai tahan lama'],
            ['category_id' => 1, 'name' => 'Keyboard Mechanical', 'price' => 500000, 'stock' => 15, 'min_stock' => 3, 'unit' => 'pcs', 'description' => 'Keyboard mechanical RGB dengan switch blue'],
            ['category_id' => 1, 'name' => 'Headset Gaming', 'price' => 350000, 'stock' => 20, 'min_stock' => 5, 'unit' => 'pcs', 'description' => 'Headset gaming dengan surround sound 7.1'],
            ['category_id' => 2, 'name' => 'Kaos Polos', 'price' => 75000, 'stock' => 50, 'min_stock' => 10, 'unit' => 'pcs', 'description' => 'Kaos polos cotton combed 30s'],
            ['category_id' => 2, 'name' => 'Celana Jeans', 'price' => 250000, 'stock' => 30, 'min_stock' => 8, 'unit' => 'pcs', 'description' => 'Celana jeans premium stretch'],
            ['category_id' => 3, 'name' => 'Kopi Arabica 250gr', 'price' => 85000, 'stock' => 40, 'min_stock' => 10, 'unit' => 'pack', 'description' => 'Kopi arabica premium roasted'],
            ['category_id' => 3, 'name' => 'Teh Hijau 100gr', 'price' => 45000, 'stock' => 35, 'min_stock' => 10, 'unit' => 'pack', 'description' => 'Teh hijau organik berkualitas'],
            ['category_id' => 4, 'name' => 'Pulpen Gel', 'price' => 5000, 'stock' => 100, 'min_stock' => 20, 'unit' => 'pcs', 'description' => 'Pulpen gel tinta hitam 0.5mm'],
            ['category_id' => 4, 'name' => 'Buku Tulis A5', 'price' => 12000, 'stock' => 80, 'min_stock' => 15, 'unit' => 'pcs', 'description' => 'Buku tulis 80 lembar'],
            ['category_id' => 4, 'name' => 'Penghapus Putih', 'price' => 3000, 'stock' => 150, 'min_stock' => 30, 'unit' => 'pcs', 'description' => 'Penghapus putih kualitas premium'],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Create services
        $services = [
            ['category_id' => 5, 'name' => 'Cuci Mobil Premium', 'price' => 75000, 'duration' => 60, 'description' => 'Cuci mobil lengkap dengan wax dan poles'],
            ['category_id' => 5, 'name' => 'Potong Rambut Pria', 'price' => 50000, 'duration' => 30, 'description' => 'Potong rambut pria dengan styling'],
            ['category_id' => 5, 'name' => 'Facial Treatment', 'price' => 150000, 'duration' => 90, 'description' => 'Perawatan wajah lengkap dengan masker'],
            ['category_id' => 6, 'name' => 'Service Laptop', 'price' => 200000, 'duration' => 120, 'description' => 'Service dan pembersihan laptop menyeluruh'],
            ['category_id' => 6, 'name' => 'Perbaikan HP', 'price' => 150000, 'duration' => 60, 'description' => 'Perbaikan hardware dan software HP'],
            ['category_id' => 7, 'name' => 'Konsultasi IT', 'price' => 300000, 'duration' => 60, 'description' => 'Konsultasi teknologi informasi'],
            ['category_id' => 7, 'name' => 'Konsultasi Bisnis', 'price' => 500000, 'duration' => 90, 'description' => 'Konsultasi strategi bisnis'],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
