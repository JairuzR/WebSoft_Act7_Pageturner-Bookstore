@extends('layouts.app')

@section('title', 'Manage Orders - Admin')

@section('header')
    <h1 class="text-3xl font-bold text-gray-900">Manage Orders</h1>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($orders as $order)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900">#{{ $order->id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $order->user->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-brown-600">${{ number_format($order->total_amount, 2) }}</td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="flex items-center space-x-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="text-sm border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit" class="text-brown-600 hover:text-brown-900 text-sm">Update</button>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('orders.show', $order) }}" class="text-brown-600 hover:text-brown-900">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
@endsection