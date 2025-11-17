@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <form action="{{ route('categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('name') border-red-500 @enderror"
                            placeholder="Masukkan nama kategori">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe *</label>
                        <select name="type" id="type" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('type') border-red-500 @enderror">
                            <option value="">Pilih Tipe</option>
                            <option value="product" {{ old('type', $category->type) == 'product' ? 'selected' : '' }}>Produk
                            </option>
                            <option value="service" {{ old('type', $category->type) == 'service' ? 'selected' : '' }}>Jasa
                            </option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('description') border-red-500 @enderror"
                            placeholder="Deskripsi kategori (opsional)">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                            {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">
                            Aktif
                        </label>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                        <a href="{{ route('categories.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            Update Kategori
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
