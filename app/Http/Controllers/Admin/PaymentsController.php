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
    $orders = Order::whereIn('status', ['pending', 'paid'])
                    ->latest()
                    ->paginate(20);
    return view('admin.payments.index', compact('orders'));
}

    public function show(Order $order)
    {
        // Opsional: pastikan hanya order dengan status 'pending' yang bisa dilihat
        if ($order->status !== 'pending') {
            abort(404);
        }

        return view('admin.payments.show', compact('order'));
    }

    public function confirm(Request $request, Order $order)
    {
        // Jangan konfirmasi jika status bukan 'pending'
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Order ini tidak dalam status menunggu pembayaran.');
        }

        // Update status
        $order->status = 'paid';

        // Set payment_confirmed_at jika kolom ada
        if (Schema::hasColumn('orders', 'payment_confirmed_at')) {
            $order->payment_confirmed_at = now();
        }

        // Otomatis generate tiket setelah konfirmasi
        if (Schema::hasColumn('orders', 'tickets_generated') && !$order->tickets_generated) {
            $order->tickets_generated = 1;
            if (Schema::hasColumn('orders', 'tickets_generated_at')) {
                $order->tickets_generated_at = now();
            }
        }

        $order->save();

        Log::info("Payment confirmed by admin for order {$order->id}");

        return redirect()->route('admin.payments.index')
                         ->with('success', 'Pembayaran dikonfirmasi dan tiket telah digenerate.');
    }

    public function refund(Request $request, Order $order)
    {
        // Hanya refund order yang bisa direfund (misal: paid atau confirmed)
        if (!in_array($order->status, ['paid', 'confirmed'])) {
            return redirect()->back()->with('error', 'Order ini tidak bisa direfund.');
        }

        $order->status = 'refunded';

        if (Schema::hasColumn('orders', 'refunded_at')) {
            $order->refunded_at = now();
        }

        $order->save();

        Log::info("Refund processed (admin stub) for order {$order->id}");

        return redirect()->route('admin.payments.index')
                         ->with('success', 'Refund telah ditandai.');
    }
}