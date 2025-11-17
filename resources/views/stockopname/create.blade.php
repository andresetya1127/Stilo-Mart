@extends('layouts.app')

@section('title', 'Stock Opname - Input Fisik')
@section('page-title', 'Stock Opname - Input Fisik')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Input Stok Fisik</h2>
        <form action="{{ route('stockopname.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Alasan Penyesuaian *</label>
                <textarea name="reason" id="reason" rows="3" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('reason') border-red-500 @enderror"
                    placeholder="Masukkan alasan penyesuaian stok (misalnya: Stock Opname November 2025)">{{ old('reason') }}</textarea>
                @error('reason')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="overflow-x-auto mb-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Sistem</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Fisik *</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($products as $product)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if ($product->image)
                                        <img class="h-8 w-8 rounded object-cover" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                    @endif
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $product->sku }} / {{ $product->barcode ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $product->stock }} {{ $product->unit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="hidden" name="physical_counts[{{ $loop->index }}][id]" value="{{ $product->id }}">
                                <input type="number" name="physical_counts[{{ $loop->index }}][physical]" value="{{ old('physical_counts.' . $loop->index . '.physical', $product->stock) }}" required min="0"
                                    class="w-20 px-2 py-1 border border-gray-300 rounded focus:ring-2 focus:ring-indigo-500 @error('physical_counts.' . $loop->index . '.physical') border-red-500 @enderror"
                                    placeholder="0">
                                @error('physical_counts.' . $loop->index . '.physical')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">Tidak ada produk tersedia.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('stockopname.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    Simpan Penyesuaian
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
