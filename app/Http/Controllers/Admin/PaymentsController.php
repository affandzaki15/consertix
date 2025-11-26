<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function index()
    {
        $orders = Order::whereNotNull('payment_proof')->latest()->paginate(25);
        return view('admin.payments.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('admin.payments.show', compact('order'));
    }

    public function confirm(Request $request, Order $order)
    {
        $order->payment_status = 'confirmed';
        $order->save();

        return redirect()->route('admin.payments.index')->with('success', 'Pembayaran dikonfirmasi.');
    }

    public function refund(Request $request, Order $order)
    {
        // stub: implement refund logic
        $order->payment_status = 'refunded';
        $order->save();

        return redirect()->route('admin.payments.index')->with('success', 'Refund diproses.');
    }
}