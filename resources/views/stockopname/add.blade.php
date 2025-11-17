@extends('layouts.app')

@section('title', 'Tambah Stok')
@section('page-title', 'Tambah Stok')

@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Tambah Stok Manual</h2>
            <form action="{{ route('stockopname.storeAddition') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Produk *</label>

                    <!-- Custom Tailwind Dropdown for Product Selection -->
                    <div class="relative">
                        <input type="text" id="product_search" placeholder="Cari produk berdasarkan nama, SKU, atau barcode..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 placeholder-gray-500 pr-10" required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>

                        <!-- Dropdown Results -->
                        <div id="product-dropdown" class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto hidden">
                            <div id="product-results"></div>
                            <div id="no-results" class="px-3 py-2 text-gray-500 text-sm hidden">Produk tidak ditemukan</div>
                        </div>
                    </div>

                    <input type="hidden" name="product_id" id="product_id" value="{{ old('product_id') }}" required>
                    @error('product_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Barcode Input (Optional, for quick scan) -->
                {{-- <div class="mb-6">
                    <label for="barcode" class="block text-sm font-medium text-gray-700 mb-2">Atau Scan Barcode</label>
                    <input type="text" name="barcode" id="barcode" placeholder="Scan barcode untuk memilih produk"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('barcode') border-red-500 @enderror"
                        value="{{ old('barcode') }}" autocomplete="off">
                    @error('barcode')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div> --}}

                <div id="product-info" class="mt-3 p-3 bg-gray-50 rounded-lg hidden mb-3">
                    <div class="flex items-center space-x-3">
                        <div id="product-avatar"
                            class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center">
                            <span id="product-initial" class="text-gray-500 text-xs"></span>
                        </div>
                        <div>
                            <h4 id="product-name" class="font-medium text-gray-900"></h4>
                            <p id="product-stock" class="text-sm text-gray-500"></p>
                            <p id="product-sku" class="text-sm text-gray-400"></p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Tambahan *</label>
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" required
                        min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('quantity') border-red-500 @enderror"
                        placeholder="Masukkan jumlah stok yang akan ditambahkan">
                    @error('quantity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Alasan Penambahan
                        (Opsional)</label>
                    <textarea name="reason" id="reason" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                        placeholder="Masukkan alasan penambahan stok (misalnya: Pembelian baru, Return dari pelanggan)">{{ old('reason') }}</textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('stockopname.index') }}"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Tambah Stok
                    </button>
                </div>
            </form>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const productSearch = document.getElementById('product_search');
                const productDropdown = document.getElementById('product-dropdown');
                const productResults = document.getElementById('product-results');
                const noResults = document.getElementById('no-results');
                const productIdInput = document.getElementById('product_id');
                const productInfo = document.getElementById('product-info');
                const productName = document.getElementById('product-name');
                const productStock = document.getElementById('product-stock');
                const productSku = document.getElementById('product-sku');
                const productInitial = document.getElementById('product-initial');
                const productAvatar = document.getElementById('product-avatar');
                const barcodeInput = document.getElementById('barcode');

                let searchTimeout;

                // Custom dropdown search
                productSearch.addEventListener('input', debounce(() => {
                    const query = productSearch.value.trim();
                     productInfo.classList.add('hidden');

                    if (query.length < 1) {
                        hideDropdown();
                        return;
                    }

                    searchProducts(query);
                }, 300));

                function searchProducts(query) {
                    fetch(`{{ route('stockopname.searchName') }}?q=${encodeURIComponent(query)}`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.products.length > 0) {
                            displaySearchResults(data.products);
                            showDropdown();
                        } else {
                            showNoResults();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNoResults();
                    });
                }

                function displaySearchResults(products) {
                    productResults.innerHTML = '';
                    products.forEach(product => {
                        const resultItem = document.createElement('div');
                        resultItem.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0 flex items-center space-x-3';
                        resultItem.innerHTML = `
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-600 text-sm font-medium">${product.name.charAt(0).toUpperCase()}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">${product.name}</p>
                                <p class="text-xs text-gray-500">SKU: ${product.sku} | Stok: ${product.stock} ${product.unit}</p>
                            </div>
                        `;
                        resultItem.addEventListener('click', function() {
                            selectProduct(product);
                        });
                        productResults.appendChild(resultItem);
                    });
                    noResults.classList.add('hidden');
                }

                function showNoResults() {
                    productResults.innerHTML = '';
                    noResults.classList.remove('hidden');
                    showDropdown();
                }

                function selectProduct(product) {
                    productSearch.value = product.name;
                    productIdInput.value = product.id;
                    hideDropdown();
                    displayProductInfo(product);
                }

                function showDropdown() {
                    productDropdown.classList.remove('hidden');
                }

                function hideDropdown() {
                    productDropdown.classList.add('hidden');
                }

                // Hide dropdown on click outside
                document.addEventListener('click', function(e) {
                    if (!productSearch.contains(e.target) && !productDropdown.contains(e.target)) {
                        hideDropdown();
                    }
                });

                // Barcode logic (updated to integrate with custom search)
                barcodeInput.addEventListener('input', debounce(() => {
                    const barcode = barcodeInput.value.trim();
                    if (barcode.length < 3) {
                        clearProductInfo();
                        return;
                    }

                    searchProductByBarcode(barcode);
                }, 300));

                barcodeInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const barcode = this.value.trim();
                        if (barcode) {
                            searchProductByBarcode(barcode);
                        }
                    }
                });

                function searchProductByBarcode(barcode) {
                    fetch(`{{ route('stockopname.searchBarcode') }}?q=${encodeURIComponent(barcode)}`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const product = data.product;
                            productSearch.value = product.name;
                            productIdInput.value = product.id;
                            barcodeInput.classList.remove('border-red-500');
                            displayProductInfo(product);
                        } else {
                            clearProductInfo();
                            productIdInput.value = '';
                            showError(data.message);
                            barcodeInput.classList.add('border-red-500');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        clearProductInfo();
                        productIdInput.value = '';
                        showError('Terjadi kesalahan saat mencari produk');
                        barcodeInput.classList.add('border-red-500');
                    });
                }

                function displayProductInfo(product) {
                    productName.textContent = product.name;
                    productStock.textContent = `Stok saat ini: ${product.stock} ${product.unit}`;
                    productSku.textContent = `SKU: ${product.sku}`;
                    productInitial.textContent = product.name.charAt(0).toUpperCase();
                    productInfo.classList.remove('hidden');
                }

                function clearProductInfo() {
                    productInfo.classList.add('hidden');
                    productIdInput.value = '';
                    productSearch.value = '';
                }

                function showError(message) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Produk Tidak Ditemukan',
                        text: message,
                        confirmButtonColor: '#3b82f6',
                        timer: 3000,
                        timerProgressBar: true
                    });
                }

                function debounce(func, wait) {
                    let timeout;
                    return function executedFunction(...args) {
                        const later = () => {
                            clearTimeout(timeout);
                            func(...args);
                        };
                        clearTimeout(timeout);
                        timeout = setTimeout(later, wait);
                    };
                }
            });
        </script>
    </div>
@endsection
