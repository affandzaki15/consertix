<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class PaymentsController extends Controller
{
    public function index()
    {
        $orders = Order::whereNotNull('payment_proof')->latest()->paginate(20);
        return view('admin.payments.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('admin.payments.show', compact('order'));
    }

    public function confirm(Request $request, Order $order)
    {
        // set payment status / order status safely
        if (Schema::hasColumn('orders', 'payment_status')) {
            $order->payment_status = 'confirmed';
        }
        if (Schema::hasColumn('orders', 'status')) {
            $order->status = 'paid';
        }
        if (Schema::hasColumn('orders', 'payment_confirmed_at')) {
            $order->payment_confirmed_at = now();
        }

        // Optionally mark tickets generated
        if (Schema::hasColumn('orders', 'tickets_generated') && ! $order->tickets_generated) {
            $order->tickets_generated = 1;
            if (Schema::hasColumn('orders', 'tickets_generated_at')) {
                $order->tickets_generated_at = now();
            }
        }

        $order->save();

        // log for trace
        Log::info("Payment confirmed by admin for order {$order->id}");

        return redirect()->back()->with('success', 'Pembayaran dikonfirmasi dan tiket dihasilkan (jika ada).');
    }

    public function refund(Request $request, Order $order)
    {
        // stub refund: mark order payment_status/status
        if (Schema::hasColumn('orders', 'payment_status')) {
            $order->payment_status = 'refunded';
        }
        if (Schema::hasColumn('orders', 'status')) {
            $order->status = 'refunded';
        }
        if (Schema::hasColumn('orders', 'refunded_at')) {
            $order->refunded_at = now();
        }

        $order->save();

        Log::info("Refund processed (admin stub) for order {$order->id}");

        return redirect()->back()->with('success', 'Refund ditandai (stub).');
    }
}