@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Edit Profile</h2>

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror"
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror"
                       required>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @if(auth()->user()->isAdmin())
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select id="role" name="role"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('role') border-red-500 @enderror">
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="kasir" {{ old('role', $user->role) == 'kasir' ? 'selected' : '' }}>Kasir</option>
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            @endif

            <div class="flex justify-end space-x-3">
                <a href="{{ route('dashboard') }}" class="px-4 py-2 text-gray-600 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 text-white bg-indigo-600 rounded-md hover:bg-indigo-700 transition-colors">
                    Update Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
