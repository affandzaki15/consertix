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
        // Ambil order tanpa perlu relasi user karena data buyer disimpan langsung di order
        $orders = Order::latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

     public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

        public function generateTickets(Request $request, Order $order)
{
    if ($order->status !== 'paid') {
        return redirect()->back()->with('error', 'Hanya order yang sudah dibayar yang bisa digenerate.');
    }

    // Generate tiket
    $order->tickets_generated = 1;
    $order->tickets_generated_at = now();
    $order->status = 'completed'; // ← ubah status jadi completed
    $order->save();

    return redirect()->back()->with('success', 'Tiket berhasil digenerate. Status diubah ke "Selesai".');
}

}