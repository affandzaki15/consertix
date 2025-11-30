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
        // Cek apakah status sudah "completed"
        if ($order->status !== 'completed') {
            return redirect()->back()->with('error', 'Tiket hanya bisa digenerate setelah status order menjadi "completed".');
        }

        // Jika sudah completed, generate tiket
        if (Schema::hasColumn('orders', 'tickets_generated')) {
            $order->tickets_generated = 1;
        }
        if (Schema::hasColumn('orders', 'tickets_generated_at')) {
            $order->tickets_generated_at = now();
        }

        $order->save();

        return redirect()->back()->with('success', 'Tiket telah berhasil digenerate.');
    }

}