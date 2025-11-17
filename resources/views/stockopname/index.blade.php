@extends('layouts.app')

@section('title', 'Stock Opname')
@section('page-title', 'Stock Opname')

@section('content')
    <div class="mx-auto">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <!-- Summary Cards -->
            <div class="flex justify-end items-center gap-2 mb-6">
                {{-- <h2 class="text-2xl font-bold text-gray-900">Stock Opname & Penambahan Stok</h2> --}}
                <a href="{{ route('stockopname.add') }}"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    Tambah Stok
                </a>
                <a href="{{ route('stockopname.create') }}"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                    Stock Opname Fisik
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-indigo-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-indigo-800 mb-1">Total Penambahan (Semua)</h3>
                    <p class="text-2xl font-bold text-indigo-600">{{ $counts['all'] }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-green-800 mb-1">Minggu Ini</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $counts['week'] }}</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-blue-800 mb-1">Bulan Ini</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $counts['month'] }}</p>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-purple-800 mb-1">Tahun Ini</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ $counts['year'] }}</p>
                </div>
            </div>

            <!-- Filter -->
            <div class="mb-6">
                <form method="GET" action="{{ route('stockopname.index') }}">
                    <div class="flex items-center space-x-2">
                        <label for="period" class="text-sm font-medium text-gray-700">Filter Periode:</label>
                        <select name="period" id="period" onchange="this.form.submit()"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="all" {{ $period == 'all' ? 'selected' : '' }}>Semua</option>
                            <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                            <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                            <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Tahun Ini</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- History of Additions -->
            @if ($adjustments->count() > 0)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Penambahan Stok</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Produk</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Alasan</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        User</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($adjustments as $adjustment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $adjustment->product->name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            +{{ $adjustment->quantity }} {{ $adjustment->product->unit }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                                            {{ Str::limit($adjustment->reason, 50) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $adjustment->user->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $adjustment->created_at->format('d M Y H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $adjustments->appends(['period' => $period])->links() }}
                    </div>
                </div>
            @endif

            <!-- Products List -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Produk</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Produk
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Stok
                                    Saat Ini</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($products as $product)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if ($product->image)
                                                <img class="h-10 w-10 rounded-full object-cover"
                                                    src="{{ asset('storage/' . $product->image) }}"
                                                    alt="{{ $product->name }}">
                                            @else
                                                <div
                                                    class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <span
                                                        class="text-gray-500 text-xs">{{ substr($product->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $product->sku }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $product->category->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $product->is_low_stock ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $product->stock }} {{ $product->unit }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $product->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada produk
                                        tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
