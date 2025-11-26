<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->paginate(25);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    public function generateTickets(Order $order)
    {
        // stub: implement ticket generation logic
        // $order->generateTickets();
        return redirect()->route('admin.orders.show', $order)->with('success', 'Tiket dihasilkan.');
    }
}