<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BookApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Book::with('category');
        
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('author', 'like', '%' . $request->search . '%');
            });
        }
        
        $books = $query->paginate(20);
        
        return response()->json([
            'success' => true,
            'data' => $books,
        ]);
    }
    
    public function show(Book $book): JsonResponse
    {
        $book->load('category', 'reviews.user');
        
        return response()->json([
            'success' => true,
            'data' => $book,
        ]);
    }
    
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);
        
        $book = Book::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Book created successfully',
            'data' => $book,
        ], 201);
    }
    
    public function update(Request $request, Book $book): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'title' => 'sometimes|string|max:255',
            'author' => 'sometimes|string|max:255',
            'isbn' => 'sometimes|string|unique:books,isbn,' . $book->id,
            'price' => 'sometimes|numeric|min:0',
            'stock_quantity' => 'sometimes|integer|min:0',
            'description' => 'nullable|string',
        ]);
        
        $book->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Book updated successfully',
            'data' => $book,
        ]);
    }
    
    public function destroy(Book $book): JsonResponse
    {
        $book->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Book deleted successfully',
        ]);
    }
}