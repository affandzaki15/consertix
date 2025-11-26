<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    public function generateTickets(Request $request, Order $order)
    {
        // safe: set tickets_generated flag if column exists, otherwise mark status -> completed
        if (Schema::hasColumn('orders', 'tickets_generated')) {
            $order->tickets_generated = 1;
        }
        // set order status if applicable
        if (Schema::hasColumn('orders', 'status')) {
            $order->status = 'completed';
        }
        if (Schema::hasColumn('orders', 'tickets_generated_at')) {
            $order->tickets_generated_at = now();
        }
        $order->save();

        return redirect()->back()->with('success', 'Tiket telah di-generate (stub).');
    }
}