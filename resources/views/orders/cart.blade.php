@extends('layouts.app')

@section('title', 'Shopping Cart - PageTurner')

@section('header')
    <h1 class="text-3xl font-bold text-gray-900">Shopping Cart</h1>
@endsection

@section('content')
    @if(count($cartItems) > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($cartItems as $item)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded">
                                    @if($item['book']->cover_image)
                                        <img src="{{ asset('storage/' . $item['book']->cover_image) }}" 
                                             alt="{{ $item['book']->title }}"
                                             class="h-10 w-10 object-cover rounded">
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $item['book']->title }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        by {{ $item['book']->author }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            ${{ number_format($item['book']->price, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('orders.cart.update') }}" method="POST" class="flex items-center space-x-2">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $item['book']->id }}">
                                <input type="number" 
                                       name="quantity" 
                                       value="{{ $item['quantity'] }}" 
                                       min="0" 
                                       max="{{ $item['book']->stock_quantity }}"
                                       class="w-16 border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500">
                                <button type="submit" class="text-brown-600 hover:text-brown-900 text-sm">
                                    Update
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            ${{ number_format($item['subtotal'], 2) }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('orders.cart.remove', $item['book']->id) }}" 
                               class="text-red-600 hover:text-red-900"
                               onclick="return confirm('Remove this item from cart?')">
                                Remove
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right font-medium text-gray-900">
                            Total:
                        </td>
                        <td class="px-6 py-4 text-lg font-bold text-brown-600">
                            ${{ number_format($total, 2) }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="mt-6 flex justify-between">
            <a href="{{ route('books.index') }}" 
               class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 transition">
                Continue Shopping
            </a>
            <a href="{{ route('orders.checkout') }}" 
               class="bg-brown-600 text-white px-6 py-2 rounded-md hover:bg-brown-700 transition">
                Proceed to Checkout
            </a>
        </div>
    @else
        <x-alert type="info">
            Your cart is empty. <a href="{{ route('books.index') }}" class="text-brown-600 hover:underline">Browse books</a> to add some items!
        </x-alert>
    @endif
@endsection