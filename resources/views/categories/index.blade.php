@extends('layouts.app')

@section('title', 'Categories - PageTurner')

@section('header')
    <h1 class="text-3xl font-bold text-gray-900">Book Categories</h1>
@endsection

@section('content')
    @auth
        @if(auth()->user()->isAdmin())
            <div class="mb-6">
                <a href="{{ route('admin.categories.create') }}" 
                   class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                    Add New Category
                </a>
            </div>
        @endif
    @endauth
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($categories as $category)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">
                        <a href="{{ route('categories.show', $category) }}" class="hover:text-indigo-600">
                            {{ $category->name }}
                        </a>
                    </h2>
                    <p class="text-gray-600 mb-4">{{ Str::limit($category->description, 100) }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-indigo-600 font-medium">{{ $category->books_count }} books</span>
                        <a href="{{ route('categories.show', $category) }}" 
                           class="text-indigo-600 hover:text-indigo-800">
                            Browse Books →
                        </a>
                    </div>
                    
                    @auth
                        @if(auth()->user()->isAdmin())
                            <div class="mt-4 pt-4 border-t border-gray-200 flex space-x-2">
                                <a href="{{ route('admin.categories.edit', $category) }}" 
                                   class="text-yellow-600 hover:text-yellow-800 text-sm">
                                    Edit
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="mt-8">
        {{ $categories->links() }}
    </div>
@endsection