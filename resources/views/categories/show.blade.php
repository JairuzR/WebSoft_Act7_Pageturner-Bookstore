@extends('layouts.app')

@section('title', $category->name . ' - PageTurner')

@section('header')
    <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
    <p class="text-gray-600 mt-2">{{ $category->description }}</p>
@endsection

@section('content')
    <div class="mb-6">
        <p class="text-lg text-gray-600">{{ $category->books->count() }} books in this category</p>
    </div>
    
    @if($books->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($books as $book)
                <x-book-card :book="$book" />
            @endforeach
        </div>
        
        <div class="mt-8">
            {{ $books->links() }}
        </div>
    @else
        <x-alert type="info">
            No books found in this category.
        </x-alert>
    @endif
@endsection