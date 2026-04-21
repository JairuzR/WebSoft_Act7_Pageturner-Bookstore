@extends('layouts.app')

@section('title', 'Order Details - PageTurner')

@section('header')
    <h1 class="text-3xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-lg font-semibold mb-2">Order Information</h2>
                <p class="text-gray-600">Date: {{ $order->created_at->format('F j, Y, g:i a') }}</p>
                <p class="text-gray-600">Status: 
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        @if($order->status == 'completed') bg-green-100 text-green-800
                        @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>
                <p class="text-gray-600">Payment Method: 
                    <span class="font-medium">
                        @switch($order->payment_method)
                            @case('credit_card') Credit Card @break
                            @case('paypal') PayPal @break
                            @case('bank_transfer') Bank Transfer @break
                            @case('gcash') GCash @break
                            @case('card') Card @break
                            @case('cod') Cash on Delivery @break
                            @default {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                        @endswitch
                    </span>
                </p>
                <p class="text-gray-600">Shipping Address: {{ $order->shipping_address }}</p>
                <p class="text-gray-600">Total Amount: <span class="font-bold text-brown-600">${{ number_format($order->total_amount, 2) }}</span></p>
            </div>
            
            <div>
                <h2 class="text-lg font-semibold mb-2">Customer Information</h2>
                <p class="text-gray-600">Name: {{ $order->user->name }}</p>
                <p class="text-gray-600">Email: {{ $order->user->email }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <h2 class="text-lg font-semibold p-6 border-b">Order Items</h2>
        
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($order->orderItems as $item)
                <tr>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $item->book->title }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    by {{ $item->book->author }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        ${{ number_format($item->unit_price, 2) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $item->quantity }}
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-brown-600">
                        ${{ number_format($item->subtotal, 2) }}
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
                        ${{ number_format($order->total_amount, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <div class="mt-6">
        <a href="{{ route('orders.index') }}" class="text-brown-600 hover:text-brown-900">
            ← Back to My Orders
        </a>
    </div>
@endsection