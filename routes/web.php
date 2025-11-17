<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Auth;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

    // Authenticated Routes
    Route::middleware('auth')->group(function () {
        // Logout
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Dashboard (accessible to all)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Profile (accessible to all)
        Route::get('/profile', function () {
            return view('profile.edit', ['user' => Auth::user()]);
        })->name('profile.edit');

        Route::put('/profile', function (Illuminate\Http\Request $request) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . auth()->id(),
            ]);

            Auth::user()->update($request->only('name', 'email'));

            return redirect()->route('dashboard')->with('success', 'Profile updated successfully.');
        })->name('profile.update');

        // Cashier / POS (accessible to all)
        Route::get('/cashier', [CashierController::class, 'index'])->name('cashier.index');
        Route::post('/cashier/process', [CashierController::class, 'process'])->name('cashier.process');
        Route::get('/cashier/search', [CashierController::class, 'search'])->name('cashier.search');

        // Admin-only routes
        Route::middleware('role:admin')->group(function () {
            // Categories
            Route::resource('categories', CategoryController::class);

            // Products
            Route::resource('products', ProductController::class);
            Route::post('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');

            // Services
            Route::resource('services', ServiceController::class);
            Route::post('services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status');

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
    Route::get('/transactions/pdf', [TransactionController::class, 'pdf'])->name('transactions.pdf');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/{transaction}/print', [TransactionController::class, 'print'])->name('transactions.print');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

            // Stock Opname
            Route::resource('stockopname', StockOpnameController::class)->only(['index', 'create', 'store']);
            Route::get('/stockopname/add', [StockOpnameController::class, 'addStock'])->name('stockopname.add');
            Route::post('/stockopname/add', [StockOpnameController::class, 'storeAddition'])->name('stockopname.storeAddition');
            Route::get('/stockopname/search-barcode', [StockOpnameController::class, 'searchByBarcode'])->name('stockopname.searchBarcode');
            Route::get('/stockopname/search-name', [StockOpnameController::class, 'searchByName'])->name('stockopname.searchName');
        });
    });
