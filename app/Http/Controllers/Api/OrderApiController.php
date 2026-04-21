<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrderApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $orders = Order::with('orderItems.book')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }
    
    public function store(Request $request): JsonResponse
    {
        // Similar to web OrderController@store but returns JSON
        // Implementation omitted for brevity
        // ...
    }
}