@extends('layouts.app')

@section('title', 'Kasir')
@section('page-title', 'Kasir / POS')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Products & Services List -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Tabs -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-4">
                    <div class="relative">
                        <input type="text" id="searchInput" placeholder="Cari produk atau jasa..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button onclick="switchTab('products')" id="productsTab"
                            class="tab-button active px-6 py-4 text-sm font-medium border-b-2 border-indigo-500 text-indigo-600">
                            Produk
                        </button>
                        <button onclick="switchTab('services')" id="servicesTab"
                            class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Jasa
                        </button>
                    </nav>
                </div>

                <!-- Products Grid -->
                <div id="productsContent" class="tab-content p-4">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 max-h-[600px] overflow-y-auto">
                        @foreach ($products as $product)
                            <div class="product-card bg-white border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-all cursor-pointer transform hover:scale-105"
                                onclick="addToCart('product', {{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, {{ $product->stock }})">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                        class="w-full h-32 object-cover rounded-lg mb-3">
                                @else
                                    <img src="{{ asset('logo.png') }}" alt="{{ $product->name }}"
                                        class="w-full h-32 object-cover rounded-lg mb-3">
                                @endif
                                <h4 class="font-semibold text-gray-800 text-sm mb-1 truncate">{{ $product->name }}</h4>
                                <p
                                    class="text-xs mb-2 {{ $product->stock >= $product->min_stock ? 'text-gray-500' : 'text-red-500' }}">
                                    Stok: {{ $product->stock . ' ' . $product->unit }}
                                </p>
                                <p class="text-indigo-600 font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Services Grid -->
                <div id="servicesContent" class="tab-content hidden p-4">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 max-h-[600px] overflow-y-auto">
                        @foreach ($services as $service)
                            <div class="service-card bg-white border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-all cursor-pointer transform hover:scale-105"
                                onclick="addToCart('service', {{ $service->id }}, '{{ $service->name }}', {{ $service->price }}, 999)">
                                @if ($service->image)
                                    <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}"
                                        class="w-full h-32 object-cover rounded-lg mb-3">
                                @else
                                    <img src="{{ asset('logo.png') }}" alt="{{ $product->name }}"
                                        class="w-full h-32 object-cover rounded-lg mb-3">
                                @endif
                                <h4 class="font-semibold text-gray-800 text-sm mb-1 truncate">{{ $service->name }}</h4>
                                @if ($service->duration)
                                    <p class="text-xs text-gray-500 mb-2">Durasi: {{ $service->duration }} menit</p>
                                @endif
                                <p class="text-purple-600 font-bold">Rp {{ number_format($service->price, 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart & Checkout -->
        <div class="space-y-4">
            <!-- Barcode Scanner -->
            <div class="bg-white rounded-xl shadow-sm p-4">
                <div class="relative">
                    <input type="text" id="barcodeInput" placeholder="Scan barcode atau masukkan SKU..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        autofocus>
                    <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
            </div>
            <!-- Cart -->
            <div class="bg-white rounded-xl shadow-sm p-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Keranjang</h3>

                <div id="cartItems" class="space-y-3 max-h-[400px] overflow-y-auto mb-4">
                    <div class="text-center py-8 text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <p class="text-sm">Keranjang kosong</p>
                    </div>
                </div>

                <button onclick="clearCart()"
                    class="w-full py-2 text-sm text-red-600 hover:text-red-800 font-medium border border-red-300 rounded-lg hover:bg-red-50 transition-colors">
                    Kosongkan Keranjang
                </button>
            </div>

            <!-- Summary & Payment -->
            <div class="bg-white rounded-xl shadow-sm p-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pembayaran</h3>

                <div class="space-y-3 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold" id="subtotalDisplay">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Pajak (0%)</span>
                        <span class="font-semibold" id="taxDisplay">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Diskon</span>
                        <input type="number" id="discountInput" value="0" min="0"
                            class="w-24 text-right px-2 py-1 border border-gray-300 rounded text-sm"
                            onchange="calculateTotal()">
                    </div>
                    <div class="border-t pt-3 flex justify-between">
                        <span class="font-semibold text-gray-800">Total</span>
                        <span class="font-bold text-xl text-indigo-600" id="totalDisplay">Rp 0</span>
                    </div>
                </div>

                <div class="space-y-3 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                        <select id="paymentMethod"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="cash">Tunai</option>
                            <option value="debit">Hutang</option>
                            <option value="card">Kartu</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>

                    <div id="paidInputContainer">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Bayar</label>
                        <input type="text" id="paidAmount"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                            onchange="calculateChange()" placeholder="Rp 0">
                        <p id="paidAmountHint" class="text-xs text-gray-500 mt-1 hidden">Kosongkan untuk hutang</p>
                    </div>

                    <div class="bg-gray-50 p-3 rounded-lg">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Kembalian</span>
                            <span class="font-bold text-green-600" id="changeDisplay">Rp 0</span>
                        </div>
                    </div>
                </div>

                <button onclick="processTransaction()" id="checkoutBtn"
                    class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all transform hover:scale-105 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                    Proses Transaksi
                </button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full transform transition-all">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Transaksi Berhasil!</h3>
                <p class="text-gray-600 mb-4">Invoice: <span id="invoiceNumber" class="font-semibold"></span></p>
                <div class="space-y-2">
                    <button onclick="printReceipt()"
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors">
                        Cetak Struk
                    </button>
                    <button onclick="closeModal()"
                        class="w-full bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let cart = [];
        let currentTransactionId = null;

        // Tab switching
        function switchTab(tab) {
            const tabs = ['products', 'services'];
            tabs.forEach(t => {
                document.getElementById(t + 'Content').classList.toggle('hidden', t !== tab);
                document.getElementById(t + 'Tab').classList.toggle('active', t === tab);
                document.getElementById(t + 'Tab').classList.toggle('border-indigo-500', t === tab);
                document.getElementById(t + 'Tab').classList.toggle('text-indigo-600', t === tab);
                document.getElementById(t + 'Tab').classList.toggle('border-transparent', t !== tab);
                document.getElementById(t + 'Tab').classList.toggle('text-gray-500', t !== tab);
            });
        }

        // Add to cart
        function addToCart(type, id, name, price, stock) {
            const existingItem = cart.find(item => item.type === type && item.id === id);

            if (existingItem) {
                if (type === 'product' && existingItem.quantity >= stock) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stok Tidak Cukup',
                        text: 'Stok tidak mencukupi!',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                existingItem.quantity++;
            } else {
                cart.push({
                    type,
                    id,
                    name,
                    price,
                    quantity: 1,
                    stock
                });
            }

            renderCart();
            calculateTotal();
        }

        // Remove from cart
        function removeFromCart(index) {
            cart.splice(index, 1);
            renderCart();
            calculateTotal();
        }

        // Update quantity
        function updateQuantity(index, change) {
            const item = cart[index];
            const newQuantity = item.quantity + change;

            if (newQuantity < 1) {
                removeFromCart(index);
                return;
            }

            if (item.type === 'product' && newQuantity > item.stock) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Tidak Cukup',
                    text: 'Stok tidak mencukupi!',
                    confirmButtonText: 'OK'
                });
                return;
            }

            item.quantity = newQuantity;
            renderCart();
            calculateTotal();
        }

        // Render cart
        function renderCart() {
            const cartItems = document.getElementById('cartItems');

            if (cart.length === 0) {
                cartItems.innerHTML = `
            <div class="text-center py-8 text-gray-400">
                <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p class="text-sm">Keranjang kosong</p>
            </div>
        `;
                document.getElementById('checkoutBtn').disabled = true;
                return;
            }

            cartItems.innerHTML = cart.map((item, index) => `
        <div class="bg-gray-50 rounded-lg p-3">
            <div class="flex justify-between items-start mb-2">
                <div class="flex-1">
                    <h4 class="font-semibold text-sm text-gray-800">${item.name}</h4>
                    <p class="text-xs text-gray-500">${item.type === 'product' ? 'Produk' : 'Jasa'}</p>
                </div>
                <button onclick="removeFromCart(${index})" class="text-red-500 hover:text-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <button onclick="updateQuantity(${index}, -1)" class="w-6 h-6 bg-white rounded border border-gray-300 hover:bg-gray-100">-</button>
                    <span class="text-sm font-medium w-8 text-center">${item.quantity}</span>
                    <button onclick="updateQuantity(${index}, 1)" class="w-6 h-6 bg-white rounded border border-gray-300 hover:bg-gray-100">+</button>
                </div>
                <span class="font-semibold text-indigo-600">Rp ${(item.price * item.quantity).toLocaleString('id-ID')}</span>
            </div>
        </div>
    `).join('');

            document.getElementById('checkoutBtn').disabled = false;
        }

        // Calculate total
        function calculateTotal() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tax = 0; // 0% tax
            const discount = parseFloat(document.getElementById('discountInput').value) || 0;
            const total = subtotal + tax - discount;

            document.getElementById('subtotalDisplay').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            document.getElementById('taxDisplay').textContent = 'Rp ' + tax.toLocaleString('id-ID');
            document.getElementById('totalDisplay').textContent = 'Rp ' + total.toLocaleString('id-ID');

            calculateChange();
        }

        // Calculate change
        function calculateChange() {
            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0) - (parseFloat(document
                .getElementById('discountInput').value) || 0);
            const paidInput = document.getElementById('paidAmount');
            const paid = parseFloat(paidInput.value.replace(/[^\d]/g, '')) || 0;
            const paymentMethod = document.getElementById('paymentMethod').value;
            const change = paymentMethod === 'debit' ? 0 : (paid - total);

            document.getElementById('changeDisplay').textContent = 'Rp ' + Math.max(0, change).toLocaleString('id-ID');
        }


        // Clear cart
        function clearCart() {
            Swal.fire({
                title: 'Kosongkan Keranjang?',
                text: 'Apakah Anda yakin ingin mengosongkan keranjang?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, kosongkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    cart = [];
                    renderCart();
                    calculateTotal();
                    document.getElementById('paidAmount').value = '';
                    document.getElementById('discountInput').value = '0';
                    Swal.fire(
                        'Dikosongkan!',
                        'Keranjang telah dikosongkan.',
                        'success'
                    );
                }
            });
        }

        // Process transaction
        async function processTransaction() {
            if (cart.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Keranjang Kosong',
                    text: 'Keranjang kosong!',
                    confirmButtonText: 'OK'
                });
                return;
            }

            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const discount = parseFloat(document.getElementById('discountInput').value) || 0;
            const total = subtotal - discount;
            const paidInput = document.getElementById('paidAmount');
            const paid = parseFloat(paidInput.value.replace(/[^\d]/g, '')) || 0;
            const paymentMethod = document.getElementById('paymentMethod').value;

            // Skip payment validation for debit (hutang) method
            if (paymentMethod !== 'debit' && paid < total) {
                Swal.fire({
                    icon: 'error',
                    title: 'Pembayaran Kurang',
                    text: 'Jumlah bayar kurang!',
                    confirmButtonText: 'OK'
                });
                return;
            }

            const data = {
                items: cart,
                subtotal: subtotal,
                tax: 0,
                discount: discount,
                total: total,
                paid: paymentMethod === 'debit' ? 0 : paid,
                change: paymentMethod === 'debit' ? 0 : (paid - total),
                payment_method: paymentMethod
            };

            try {
                const response = await fetch('{{ route('cashier.process') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    currentTransactionId = result.transaction_id;
                    document.getElementById('invoiceNumber').textContent = result.invoice_number;
                    document.getElementById('successModal').classList.remove('hidden');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Transaksi Gagal',
                        text: result.message || 'Transaksi gagal!',
                        confirmButtonText: 'OK'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: 'Terjadi kesalahan: ' + error.message,
                    confirmButtonText: 'OK'
                });
            }
        }

        // Close modal
        function closeModal() {
            // document.getElementById('successModal').classList.add('hidden');
            // currentTransactionId = null;
            window.location.reload();
        }

        // Print receipt
        function printReceipt() {
            if (currentTransactionId) {
                window.open(`/transactions/${currentTransactionId}/print`, '_blank');
            }
        }

        // Barcode scanning functionality
        let barcodeTimeout;
        document.getElementById('barcodeInput').addEventListener('input', function(e) {
            clearTimeout(barcodeTimeout);
            const barcode = e.target.value.trim();

            if (barcode.length < 3) return; // Minimum length for search

            barcodeTimeout = setTimeout(async () => {
                try {
                    let searchUrl = `{{ route('cashier.search') }}?q=${encodeURIComponent(barcode)}`;
                    if (isNumeric(barcode) && barcode.length >= 6) {
                        searchUrl += '&exact=true';
                    }
                    const response = await fetch(searchUrl, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (isNumeric(barcode) && barcode.length >= 6) {
                        // Exact barcode match
                        const matchedProducts = result.products.filter(p => p.barcode === barcode && p
                            .stock > 0);

                        if (matchedProducts.length === 1) {
                            const product = matchedProducts[0];
                            addToCart('product', product.id, product.name, product.price, product
                                .stock);
                            e.target.value = ''; // Clear input after add
                            Swal.fire({
                                icon: 'success',
                                title: 'Ditambahkan!',
                                text: `Ditambahkan: ${product.name}`,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else if (matchedProducts.length > 1) {
                            // Multiple products with same barcode
                            showProductSelectionModal(matchedProducts, barcode);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Produk Tidak Ditemukan',
                                text: 'Produk dengan barcode ini tidak ditemukan atau stok habis!',
                                confirmButtonText: 'OK'
                            });
                            e.target.value = '';
                        }
                    } else {
                        // Partial search
                        const matchedProduct = result.products.find(p =>
                            p.sku.toLowerCase().includes(barcode.toLowerCase()) ||
                            (p.barcode && p.barcode.toLowerCase().includes(barcode
                                .toLowerCase())) ||
                            p.name.toLowerCase().includes(barcode.toLowerCase())
                        );

                        if (matchedProduct && matchedProduct.stock > 0) {
                            addToCart('product', matchedProduct.id, matchedProduct.name, matchedProduct
                                .price, matchedProduct.stock);
                            e.target.value = ''; // Clear input after add
                            Swal.fire({
                                icon: 'success',
                                title: 'Ditambahkan!',
                                text: `Ditambahkan: ${matchedProduct.name}`,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Produk Tidak Ditemukan',
                                text: 'Produk tidak ditemukan atau stok habis!',
                                confirmButtonText: 'OK'
                            });
                            e.target.value = '';
                        }
                    }
                } catch (error) {
                    console.error('Search error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan Pencarian',
                        text: 'Terjadi kesalahan saat mencari produk.',
                        confirmButtonText: 'OK'
                    });
                    e.target.value = '';
                }
            }, 500); // Debounce 500ms
        });

        function isNumeric(str) {
            return /^\d+$/.test(str);
        }

        function showProductSelectionModal(products, barcode) {
            let html = '<div class="text-left">';
            products.forEach(product => {
                html += `
                    <div class="p-3 border-b border-gray-200 hover:bg-gray-50 cursor-pointer" onclick="selectProductFromModal(${product.id}, '${product.name}', ${product.price}, ${product.stock})">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                <span class="text-xs font-medium">${product.name.charAt(0).toUpperCase()}</span>
                            </div>
                            <div>
                                <h4 class="font-medium text-sm">${product.name}</h4>
                                <p class="text-xs text-gray-500">Stok: ${product.stock} ${product.category ? product.category.name : 'Unit'}</p>
                                <p class="text-xs font-semibold text-indigo-600">Rp ${product.price.toLocaleString('id-ID')}</p>
                            </div>
                        </div>
                    </div>
                `;
            });
            html += '</div>';

            Swal.fire({
                title: `Pilih Produk untuk Barcode: ${barcode}`,
                html: html,
                showCancelButton: true,
                showConfirmButton: false,
                // confirmButtonText: 'Batal',
                customClass: {
                    popup: 'swal-wide',
                    htmlContainer: 'max-h-96 overflow-y-auto'
                },
                width: '500px',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        }

        function selectProductFromModal(id, name, price, stock) {
            Swal.close();
            addToCart('product', id, name, price, stock);
            document.getElementById('barcodeInput').value = ''; // Clear input
            Swal.fire({
                icon: 'success',
                title: 'Ditambahkan!',
                text: `Ditambahkan: ${name}`,
                timer: 1500,
                showConfirmButton: false
            });
        }

        // Handle Enter key for barcode
        document.getElementById('barcodeInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.target.value = e.target.value.trim();
                if (e.target.value) {
                    // Trigger immediate search
                    clearTimeout(barcodeTimeout);
                    // Call the input handler manually
                    const inputEvent = new Event('input', {
                        bubbles: true
                    });
                    e.target.dispatchEvent(inputEvent);
                }
            }
        });

        // Auto-focus barcode input on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('barcodeInput').focus();
        });

        // Rupiah formatting for paid amount
        function formatRupiah(input) {
            const paymentMethod = document.getElementById('paymentMethod').value;
            let value = input.value.replace(/[^\d]/g, '');

            // Allow empty value for debit method
            if (value === '' && paymentMethod === 'debit') {
                input.value = '';
                return 0;
            }

            if (value === '') {
                input.value = '';
                return 0;
            }
            let formatted = 'Rp ' + parseInt(value).toLocaleString('id-ID');
            input.value = formatted;
            return parseInt(value);
        }

        // Event listeners for paid amount
        document.addEventListener('DOMContentLoaded', function() {
            const paidInput = document.getElementById('paidAmount');
            paidInput.addEventListener('input', function(e) {
                formatRupiah(this);
                calculateChange();
            });
            paidInput.addEventListener('blur', function(e) {
                if (this.value === '') {
                    this.value = 'Rp 0';
                }
                calculateChange();
            });
        });

        // Search functionality (client-side for manual search)
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const search = e.target.value.toLowerCase();

            document.querySelectorAll('.product-card, .service-card').forEach(card => {
                const name = card.querySelector('h4').textContent.toLowerCase();
                card.style.display = name.includes(search) ? 'block' : 'none';
            });
        });

        // Toggle paid amount input based on payment method
        function togglePaidAmountInput() {
            const paymentMethod = document.getElementById('paymentMethod').value;
            const paidInputContainer = document.getElementById('paidInputContainer');
            const paidInput = document.getElementById('paidAmount');

            if (paymentMethod === 'debit') {
                paidInputContainer.classList.add('hidden');
                paidInput.value = '';
            } else {
                paidInputContainer.classList.remove('hidden');
            }
        }

        // Add event listener for payment method change
        document.getElementById('paymentMethod').addEventListener('change', function() {
            togglePaidAmountInput();
            calculateChange();
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            togglePaidAmountInput();
        });
    </script>
@endsection
