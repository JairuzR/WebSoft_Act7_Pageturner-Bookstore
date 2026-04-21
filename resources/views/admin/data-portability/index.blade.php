@extends('layouts.app')

@section('title', 'Data Portability - Admin')

@section('header')
    <h1 class="text-3xl font-bold text-gray-900">Data Portability</h1>
    <p class="mt-2 text-gray-600">Import and export bookstore data in bulk</p>
@endsection

@section('content')
<div class="max-w-7xl mx-auto">
    
    {{-- Import Section --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4 flex items-center">
            <svg class="h-6 w-6 text-brown-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
            </svg>
            Import Books
        </h2>
        
        <p class="text-gray-600 mb-4">
            Upload an Excel or CSV file to bulk import books. The file should contain columns:
            <code class="bg-gray-100 px-2 py-1 rounded">title, author, isbn, price, stock_quantity, category, description</code>
        </p>
        
        <div class="mb-4">
            <a href="{{ route('admin.template') }}" 
               class="inline-flex items-center text-brown-600 hover:text-brown-800">
                <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download Template
            </a>
        </div>
        
        <form action="{{ route('admin.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select File (Excel, CSV)</label>
                <input type="file" 
                       name="file" 
                       accept=".xlsx,.xls,.csv"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500 @error('file') border-red-500 @enderror"
                       required>
                @error('file')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">Maximum file size: 10MB. Large files will be processed in the background.</p>
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded p-4">
                <p class="text-sm text-yellow-800">
                    <strong>Note:</strong> Imports are processed in chunks. You'll receive an email notification when the import completes.
                </p>
            </div>
            
            <button type="submit" 
                    class="bg-brown-600 text-white px-6 py-2 rounded-md hover:bg-brown-700 transition">
                Upload and Import
            </button>
        </form>
    </div>
    
    {{-- Export Section --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4 flex items-center">
            <svg class="h-6 w-6 text-brown-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Export Books
        </h2>
        
        <p class="text-gray-600 mb-4">
            Export book data with optional filters. The export will include all book details including category information.
        </p>
        
        <form action="{{ route('admin.export') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Category</label>
                    <select name="category_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500">
                        <option value="">All Categories</option>
                        @foreach(\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Title or author..."
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500">
                </div>
            </div>
            
            <button type="submit" 
                    class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition">
                Export to Excel
            </button>
        </form>
    </div>
</div>
@endsection