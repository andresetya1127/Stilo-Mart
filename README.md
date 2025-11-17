# Kasir StiloMart

Sistem Kasir Modern untuk Toko StiloMart dengan fitur lengkap untuk mengelola produk, jasa, dan transaksi penjualan.

## 🚀 Fitur Utama

-   ✅ **Login & Authentication** - Sistem login yang aman
-   ✅ **Dashboard Admin** - Overview bisnis dengan statistik real-time
-   ✅ **Kelola Produk** - CRUD produk dengan manajemen stok
-   ✅ **Kelola Jasa** - CRUD jasa dengan durasi dan harga
-   ✅ **Kasir POS** - Interface kasir modern untuk transaksi cepat
-   ✅ **Riwayat Transaksi** - Tracking semua transaksi dengan filter
-   ✅ **Cetak Struk** - Struk thermal printer friendly
-   ✅ **Responsive Design** - Tampilan menarik di semua device
-   ✅ **Vanilla JS** - Interaktivitas tanpa framework tambahan

## 🛠️ Tech Stack

-   **Backend**: Laravel 11
-   **Frontend**: Tailwind CSS v4 + Vanilla JavaScript
-   **Database**: MySQL/SQLite
-   **Build Tool**: Vite

## 📋 Requirements

-   PHP 8.2+
-   Composer
-   Node.js & NPM
-   MySQL atau SQLite

## 🚀 Instalasi & Setup

### 1. Clone Repository

```bash
git clone https://github.com/your-username/stilomart.git
cd stilomart
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup

```bash
# Setup database connection di .env file
# Contoh untuk MySQL:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stilomart
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Atau gunakan SQLite (lebih mudah untuk development):
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

### 5. Run Migrations & Seeders

```bash
# Run migrations
php artisan migrate

# Seed dummy data
php artisan db:seed
```

### 6. Storage Link

```bash
# Create storage link untuk upload gambar
php artisan storage:link
```

### 7. Build Assets

```bash
# Build untuk production
npm run build

# Atau untuk development
npm run dev
```

### 8. Start Server

```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## 👤 Akun Demo

**Admin Account:**

-   Email: `admin@stilomart.com`
-   Password: `password`

## 📁 Struktur Database

### Tables

-   `users` - Data pengguna
-   `categories` - Kategori produk/jasa
-   `products` - Data produk
-   `services` - Data jasa
-   `transactions` - Header transaksi
-   `transaction_items` - Detail item transaksi

### Dummy Data

Seeder akan membuat:

-   1 Admin user
-   7 Kategori (4 produk + 3 jasa)
-   10 Produk sample
-   7 Jasa sample

## 🎯 Cara Penggunaan

### Login

1. Buka aplikasi di browser
2. Login dengan akun admin
3. Akan diarahkan ke dashboard

### Kelola Produk

1. Klik menu "Produk" di sidebar
2. Tambah produk baru atau edit yang ada
3. Upload gambar produk (opsional)
4. Set harga, stok, dan kategori

### Kelola Jasa

1. Klik menu "Jasa" di sidebar
2. Tambah jasa baru dengan durasi dan harga
3. Kelola kategori jasa

### Kasir (POS)

1. Klik menu "Kasir"
2. Pilih tab "Produk" atau "Jasa"
3. Cari dan tambahkan item ke keranjang
4. Input pembayaran
5. Cetak struk

### Riwayat Transaksi

1. Klik menu "Transaksi"
2. Filter berdasarkan tanggal, metode pembayaran
3. Lihat detail transaksi
4. Cetak ulang struk

## 🎨 Fitur UI/UX

-   **Responsive Design** - Tampil sempurna di desktop, tablet, mobile
-   **Modern Interface** - Design clean dengan Tailwind CSS
-   **Interactive Elements** - Vanilla JS untuk animasi dan interaktivitas
-   **Toast Notifications** - Feedback real-time untuk user actions
-   **Loading States** - Indikator loading untuk UX yang baik
-   **Search & Filter** - Pencarian cepat dan filter data

## 🔧 Development

### Menjalankan Development Server

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev
```

### Menjalankan Tests

```bash
# Run PHP tests
php artisan test

# Run JavaScript tests (jika ada)
npm test
```

### Code Quality

```bash
# Format code
./vendor/bin/pint

# Analyze code
./vendor/bin/phpstan analyse
```

## 📝 API Endpoints

### Authentication

-   `POST /login` - Login user
-   `POST /logout` - Logout user

### Dashboard

-   `GET /dashboard` - Dashboard overview

### Categories

-   `GET /categories` - List categories
-   `POST /categories` - Create category
-   `PUT /categories/{id}` - Update category
-   `DELETE /categories/{id}` - Delete category

### Products

-   `GET /products` - List products
-   `POST /products` - Create product
-   `PUT /products/{id}` - Update product
-   `DELETE /products/{id}` - Delete product
-   `POST /products/{id}/toggle-status` - Toggle product status

### Services

-   `GET /services` - List services
-   `POST /services` - Create service
-   `PUT /services/{id}` - Update service
-   `DELETE /services/{id}` - Delete service
-   `POST /services/{id}/toggle-status` - Toggle service status

### Cashier

-   `GET /cashier` - POS interface
-   `POST /cashier/search-products` - Search products
-   `POST /cashier/search-services` - Search services
-   `POST /cashier/process-transaction` - Process transaction

### Transactions

-   `GET /transactions` - List transactions
-   `GET /transactions/{id}` - Show transaction detail
-   `GET /transactions/{id}/print` - Print receipt
-   `DELETE /transactions/{id}` - Delete transaction

## 🤝 Contributing

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 📞 Support

Jika ada pertanyaan atau masalah, silakan buat issue di repository ini.

---

**Dibuat dengan ❤️ untuk StiloMart**
