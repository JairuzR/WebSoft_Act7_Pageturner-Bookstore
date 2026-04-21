@extends('layouts.app')

@section('title', 'Checkout - PageTurner')

@section('header')
    <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                
                <div class="space-y-4">
                    @foreach($cartItems as $item)
                        <div class="flex justify-between items-center border-b pb-2">
                            <div>
                                <p class="font-medium">{{ $item['book']->title }}</p>
                                <p class="text-sm text-gray-600">Quantity: {{ $item['quantity'] }}</p>
                            </div>
                            <p class="font-medium">${{ number_format($item['subtotal'], 2) }}</p>
                        </div>
                    @endforeach
                    
                    <div class="flex justify-between items-center pt-2">
                        <p class="text-lg font-bold">Total</p>
                        <p class="text-xl font-bold text-brown-600">${{ number_format($total, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="md:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Shipping & Payment</h2>
                
                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="shipping_address" class="block text-gray-700 font-medium mb-2">
                            Shipping Address *
                        </label>
                        <textarea name="shipping_address" 
                                  id="shipping_address" 
                                  rows="3"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500"
                                  required>{{ old('shipping_address') }}</textarea>
                    </div>
                    
                    <div class="mb-6">
                        <label for="payment_method" class="block text-gray-700 font-medium mb-2">
                            Payment Method *
                        </label>
                        <select name="payment_method" 
                                id="payment_method"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500"
                                required>
                            <option value="">Select payment method</option>
                            <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                            <option value="paypal" {{ old('payment_method') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="gcash" {{ old('payment_method') == 'gcash' ? 'selected' : '' }}>GCash</option>
                            <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                            <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>Cash on Delivery</option>
                        </select>
                        @error('payment_method')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-brown-600 text-white px-4 py-2 rounded-md hover:bg-brown-700 transition">
                        Place Order
                    </button>
                    
                    <a href="{{ route('orders.cart') }}" 
                       class="w-full block text-center mt-2 text-gray-600 hover:text-gray-800">
                        Back to Cart
                    </a>
                </form>
            </div>
        </div>
    </div>
@endsection