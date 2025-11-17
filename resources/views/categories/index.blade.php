@extends('layouts.app')

@section('title', 'Kelola Kategori')
@section('page-title', 'Kelola Kategori')

@section('content')
    <div class="space-y-6">
        <!-- Header & Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Daftar Kategori</h2>
                <p class="text-gray-600 text-sm">Kelola kategori produk dan jasa</p>
            </div>
            <a href="{{ route('categories.create') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Kategori
            </a>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($categories as $category)
                <div
                    class="bg-white rounded-xl shadow-sm p-6 border-l-4 {{ $category->type === 'product' ? 'border-blue-500' : 'border-purple-500' }} hover:shadow-lg transition-all">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ $category->name }}</h3>
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-full {{ $category->type === 'product' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ $category->type === 'product' ? 'Produk' : 'Jasa' }}
                            </span>
                        </div>
                        <span
                            class="px-2 py-1 text-xs font-medium rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                    @if ($category->description)
                        <p class="text-sm text-gray-600 mb-4">{{ Str::limit($category->description, 100) }}</p>
                    @endif

                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div class="text-sm text-gray-500">
                            @if ($category->type === 'product')
                                {{ $category->products->count() }} produk
                            @else
                                {{ $category->services->count() }} jasa
                            @endif
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('categories.edit', $category) }}"
                                class="text-indigo-600 hover:text-indigo-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                        </path>
                    </svg>
                    <p class="text-gray-500">Belum ada kategori</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($categories->hasPages())
            <div class="flex justify-center">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
@endsection
