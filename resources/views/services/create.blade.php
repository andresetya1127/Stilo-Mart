@extends('layouts.app')

@section('title', 'Tambah Jasa')
@section('page-title', 'Tambah Jasa')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">
                    <!-- Image Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Jasa</label>
                        <div class="flex items-center justify-center w-full">
                            <label
                                class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk
                                            upload</span></p>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF (MAX. 2MB)</p>
                                </div>
                                <input type="file" name="image" class="hidden" accept="image/*"
                                    onchange="previewImage(event)">
                            </label>
                        </div>
                        <div id="imagePreview" class="mt-4 hidden">
                            <img src="" alt="Preview" class="w-full h-48 object-cover rounded-lg">
                        </div>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori *</label>
                        <select name="category_id" id="category_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('category_id') border-red-500 @enderror">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Jasa *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('name') border-red-500 @enderror"
                            placeholder="Masukkan nama jasa">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('description') border-red-500 @enderror"
                            placeholder="Deskripsi jasa">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price & Duration -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Harga *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                                <input type="number" name="price" id="price" value="{{ old('price') }}" required
                                    min="0" step="0.01"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('price') border-red-500 @enderror"
                                    placeholder="0">
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Durasi
                                (menit)</label>
                            <input type="number" name="duration" id="duration" value="{{ old('duration') }}"
                                min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('duration') border-red-500 @enderror"
                                placeholder="30">
                            @error('duration')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                            {{ old('is_active', true) ? 'checked' : '' }}
                            class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">
                            Aktif
                        </label>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                        <a href="{{ route('services.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            Simpan Jasa
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const preview = document.getElementById('imagePreview');
            const img = preview.querySelector('img');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
