<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class HistoryController extends Controller
{
    /**
     * Tampilkan semua pesanan (history) user yang sudah dibayar
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->where('status', 'paid')
            ->with(['concert', 'items.ticketType'])
            ->orderBy('paid_at', 'desc')
            ->paginate(10);

        return view('history', compact('orders'));
    }

    /**
     * Tampilkan detail tiket dengan QR code untuk pesanan tertentu
     */
    public function show(Order $order)
    {
        // Pastikan hanya owner yang bisa melihat
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Pastikan order sudah dibayar
        if ($order->status !== 'paid') {
            return redirect()->route('history')->with('error', 'Pesanan belum dibayar');
        }

        $order->load(['concert', 'items.ticketType', 'user']);

        // Generate QR code data berisi reference code dan order details
        $qrData = json_encode([
            'reference_code' => $order->reference_code,
            'order_id' => $order->id,
            'concert' => $order->concert->name,
            'date' => $order->concert->date,
            'buyer' => $order->buyer_name,
            'email' => $order->buyer_email,
        ]);

        // Generate QR code sebagai SVG
        $qrCode = QrCode::format('svg')->size(300)->generate($qrData);

        return view('history.show', [
            'order' => $order,
            'qrCode' => $qrCode,
            'referenceCode' => $order->reference_code,
        ]);
    }

    /**
     * Download tiket sebagai PDF (opsional)
     */
    public function downloadTicket(Order $order)
    {
        // Pastikan hanya owner yang bisa download
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($order->status !== 'paid') {
            return redirect()->route('history')->with('error', 'Pesanan belum dibayar');
        }

        // Generate QR code untuk PDF
        $qrData = json_encode([
            'reference_code' => $order->reference_code,
            'order_id' => $order->id,
            'concert' => $order->concert->name,
        ]);

        $qrCode = QrCode::format('svg')->size(200)->generate($qrData);

        // Untuk implementasi lengkap, install maatwebsite/excel atau barryvdh/laravel-dompdf
        // Untuk sekarang return JSON dengan data ticket
        return response()->json([
            'reference_code' => $order->reference_code,
            'concert' => $order->concert->name,
            'date' => $order->concert->date,
            'buyer_name' => $order->buyer_name,
            'buyer_email' => $order->buyer_email,
            'total_amount' => $order->total_amount,
            'items' => $order->items->map(function ($item) {
                return [
                    'ticket_type' => $item->ticketType->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ];
            }),
        ]);
    }
}
