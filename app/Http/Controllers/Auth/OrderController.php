<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem; // DAPAT NASA DITO ITO
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request) 
    {
        // 1. Validation
        $request->validate([
            'guest_name' => 'required|string',
            'table_number' => 'required',
            'items' => 'required|array',
            'total_price' => 'required',
        ]);

        try {
            // Gumamit ng Database Transaction para sigurado    
            return DB::transaction(function () use ($request) {
                
                // 2. Isave ang Main Order
                $order = Order::create([
                    'guest_name'     => $request->guest_name,
                    'table_number'   => $request->table_number,
                    'payment_method' => $request->payment_method ?? 'Cash',
                    'total_price'    => $request->total_price,
                    'status'         => 'PENDING',
                ]);

                // 3. Isave ang bawat item sa OrderItems table
                foreach($request->items as $item) {
                    // Mas mainam na gamitin ang relationship para automatic ang order_id
                    $order->items()->create([
                        'product_name' => $item['name'],
                        'price'        => $item['price'],
                        'quantity'     => $item['qty'], // Siguraduhing 'qty' ang tawag mo sa Alpine.js
                    ]);
                }

                return response()->json([
                    'success' => true, 
                    'message' => 'Order placed successfully!',
                    'order_id' => $order->id // Optional: para alam ng frontend ang ID
                ]);
            });

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}