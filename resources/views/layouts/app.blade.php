<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'StiloMart') - Kasir</title>

    <!-- jQuery for Select2 -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen">
        <!-- Navbar -->
        <nav class="bg-amber-400 text-white shadow-lg sticky top-0 z-50">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <h1 class="text-2xl font-bold">StiloMart</h1>
                        </div>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-amber-600 text-white' : 'text-amber-100 hover:bg-amber-600' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                            Dashboard
                        </a>

                        <a href="{{ route('cashier.index') }}"
                            class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('cashier.*') ? 'bg-amber-600 text-white' : 'text-amber-100 hover:bg-amber-600' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            Kasir
                        </a>

                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('products.index') }}"
                            class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('products.*') ? 'bg-amber-600 text-white' : 'text-amber-100 hover:bg-amber-600' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Produk
                        </a>

                        <a href="{{ route('services.index') }}"
                            class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('services.*') ? 'bg-amber-600 text-white' : 'text-amber-100 hover:bg-amber-600' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            Jasa
                        </a>

                        <a href="{{ route('categories.index') }}"
                            class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('categories.*') ? 'bg-amber-600 text-white' : 'text-amber-100 hover:bg-amber-600' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                </path>
                            </svg>
                            Kategori
                        </a>

                        <a href="{{ route('transactions.index') }}"
                            class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('transactions.*') ? 'bg-amber-600 text-white' : 'text-amber-100 hover:bg-amber-600' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Transaksi
                        </a>

                        <a href="{{ route('stockopname.index') }}"
                            class="flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('stockopname.*') ? 'bg-amber-600 text-white' : 'text-amber-100 hover:bg-amber-600' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                            Stock Opname
                        </a>
                        @endif
                    </div>

                    <!-- User Menu & Mobile Menu Button -->
                    <div class="flex items-center space-x-4">
                        <!-- Desktop Dropdown -->
                        <div class="hidden md:block relative">
                            <button id="user-dropdown-button" type="button"
                                class="flex items-center space-x-2 text-white hover:text-gray-200 focus:outline-none focus:text-gray-200">
                                <div class="w-8 h-8 rounded-full bg-lime-500 flex items-center justify-center">
                                    <span class="font-semibold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-amber-200">{{ auth()->user()->email }}</p>
                                </div>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="user-dropdown-menu"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden border border-gray-200">
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    Profile
                                </a>
                                <form action="{{ route('logout') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Mobile menu button -->
                        <div class="md:hidden">
                            <button id="mobile-menu-button" type="button"
                                class="text-white hover:text-gray-200 focus:outline-none focus:text-gray-200">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Navigation Menu -->
                <div id="mobile-menu" class="md:hidden hidden bg-amber-400 border-t border-amber-500">
                    <div class="px-2 pt-2 pb-3 space-y-1">
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('dashboard') ? 'bg-amber-600 text-white' : 'text-amber-100 hover:bg-amber-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                            Dashboard
                        </a>

                        <a href="{{ route('cashier.index') }}"
                            class="flex items-center px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('cashier.*') ? 'bg-amber-600 text-white' : 'text-amber-100 hover:bg-amber-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            Kasir
                        </a>

                        @if (auth()->user()->isAdmin())
                        <a href="{{ route('products.index') }}"
                            class="flex items-center px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('products.*') ? 'bg-amber-600 text-white' : 'text-amber-100 hover:bg-amber-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Produk
                        </a>

                        <a href="{{ route('services.index') }}"
                            class="flex items-center px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('services.*') ? 'bg-amber-600 text-white' : 'text-amber-100 hover:bg-amber-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            Jasa
                        </a>

                        <a href="{{ route('categories.index') }}"
                            class="flex items-center px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('categories.*') ? 'bg-amber-600 text-white' : 'text-amber-100 hover:bg-amber-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                </path>
                            </svg>
                            Kategori
                        </a>

                        <a href="{{ route('transactions.index') }}"
                            class="flex items-center px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('transactions.*') ? 'bg-amber-600 text-white' : 'text-amber-100 hover:bg-amber-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Transaksi
                        </a>

                        <a href="{{ route('stockopname.index') }}"
                            class="flex items-center px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('stockopname.*') ? 'bg-amber-600 text-white' : 'text-amber-100 hover:bg-amber-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                            Stock Opname
                        </a>
                        @endif

                        <!-- Mobile User Dropdown -->
                        <div class="border-t border-amber-600 pt-4 mt-4">
                            <button id="mobile-user-dropdown-button" type="button"
                                class="w-full flex items-center justify-between px-3 py-2 text-left text-base font-medium text-amber-100 hover:bg-amber-600">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 rounded-full bg-amber-500 flex items-center justify-center mr-3">
                                        <span class="font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ auth()->user()->name }}</p>
                                        <p class="text-sm text-amber-200">{{ auth()->user()->email }}</p>
                                    </div>
                                </div>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Mobile Dropdown Menu -->
                            <div id="mobile-user-dropdown-menu"
                                class="hidden bg-amber-600 border-t border-amber-600 mt-1 rounded-b-lg">
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-amber-100 hover:bg-amber-600 transition-colors">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    Profile
                                </a>
                                <form action="{{ route('logout') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-amber-100 hover:bg-amber-600 transition-colors">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Page Content -->
            <main class="p-6">
                @yield('content')

                @if (session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session("success") }}',
                        timer: 3000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                </script>
                @endif

                @if (session('error'))
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: '{{ session("error") }}',
                        timer: 3000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                </script>
                @endif
            </main>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton?.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', (event) => {
            if (!mobileMenuButton?.contains(event.target) && !mobileMenu?.contains(event.target)) {
                mobileMenu?.classList.add('hidden');
            }
        });

        // Desktop user dropdown toggle
        const userDropdownButton = document.getElementById('user-dropdown-button');
        const userDropdownMenu = document.getElementById('user-dropdown-menu');

        userDropdownButton?.addEventListener('click', () => {
            userDropdownMenu.classList.toggle('hidden');
        });

        // Close desktop dropdown when clicking outside
        document.addEventListener('click', (event) => {
            if (!userDropdownButton?.contains(event.target) && !userDropdownMenu?.contains(event.target)) {
                userDropdownMenu?.classList.add('hidden');
            }
        });

        // SweetAlert2 confirmation for delete actions
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('form[action*="/destroy"], form[method="POST"][_method="DELETE"]');
            deleteForms.forEach(form => {
                const submitButton = form.querySelector('button[type="submit"], input[type="submit"]') || form;
                const originalText = submitButton.textContent || submitButton.value || 'Delete';

                submitButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data ini tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>


    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>

</html>
