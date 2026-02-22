@extends('layouts.app')

@section('title', 'Edit Category - PageTurner')

@section('header')
    <h1 class="text-3xl font-bold text-gray-900">Edit Category</h1>
@endsection

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-medium mb-2">Category Name *</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $category->name) }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror" 
                           required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="description" class="block text-gray-700 font-medium mb-2">Description</label>
                    <textarea name="description" 
                              id="description" 
                              rows="4"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $category->description) }}</textarea>
                </div>
                
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('categories.index') }}" 
                       class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition">
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection