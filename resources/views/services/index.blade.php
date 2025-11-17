@extends('layouts.app')

@section('title', 'Kelola Jasa')
@section('page-title', 'Kelola Jasa')

@section('content')
    <div class="space-y-6">
        <!-- Header & Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Daftar Jasa</h2>
                <p class="text-gray-600 text-sm">Kelola jasa yang tersedia</p>
            </div>
            <a href="{{ route('services.create') }}"
                class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Jasa
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-4">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari jasa..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <select name="category"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('services.index') }}"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($services as $service)
                <div
                    class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-all transform hover:scale-105">
                    @if ($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}"
                            class="w-full h-48 object-cover">
                    @else
                       <img src="{{ asset('logo.png') }}" alt="{{ $service->name }}"
                            class="w-full h-48 object-cover">
                    @endif

                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800 mb-1">{{ $service->name }}</h3>
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                                    {{ $service->category->name }}
                                </span>
                            </div>
                            <form action="{{ route('services.toggle-status', $service) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="px-2 py-1 text-xs font-medium rounded-full {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $service->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </div>

                        @if ($service->description)
                            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($service->description, 80) }}</p>
                        @endif

                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <p class="text-xs text-gray-500">Kode</p>
                                <p class="text-sm font-medium text-gray-700">{{ $service->code }}</p>
                            </div>
                            @if ($service->duration)
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">Durasi</p>
                                    <p class="text-sm font-medium text-gray-700">{{ $service->duration }} menit</p>
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                            <p class="text-xl font-bold text-purple-600">Rp
                                {{ number_format($service->price, 0, ',', '.') }}</p>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('services.edit', $service) }}"
                                    class="text-purple-600 hover:text-purple-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </a>
                                <form action="{{ route('services.destroy', $service) }}" method="POST"
                                    onsubmit="return confirm('Hapus jasa ini?')">
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
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                    <p class="text-gray-500">Belum ada jasa</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($services->hasPages())
            <div class="flex justify-center">
                {{ $services->links() }}
            </div>
        @endif
    </div>
@endsection
