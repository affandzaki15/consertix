<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Concert;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TicketType;

class PurchaseController extends Controller
{
    public function show(Concert $concert)
    {
      // Ambil order sebelumnya (kalau ada)
    $existingOrder = Order::where('user_id', Auth::id())
        ->where('concert_id', $concert->id)
        ->first();

    return view('purchase.show', [
        'concert'       => $concert,
        'ticketTypes'   => $concert->ticketTypes,
        'user'          => Auth::user(),
        'existingOrder' => $existingOrder
    ]);
    }

    public function store(Request $request, Concert $concert)
    {
        // Cek apakah user sudah punya order untuk konser ini
        $existingOrder = Order::where('user_id', Auth::id())
            ->where('concert_id', $concert->id)
            ->latest()
            ->first();

        if ($existingOrder) {
            // Jika order sebelumnya belum selesai (mis. status != 'paid'), arahkan ke detail agar user bisa melanjutkan
            if ($existingOrder->status !== 'paid') {
                return redirect()->route('purchase.detail', $existingOrder->id)
                    ->with('error', 'Anda memiliki pesanan tertunda. Silakan lanjutkan detail pesanan.');
            }

            // Jika sudah berstatus paid, berikan pesan bahwa user sudah membeli tiket
            return back()->with('error', 'Anda sudah membeli tiket untuk konser ini.');
        }

        // Validasi: minimal ada data tiket
        if (!$request->has('ticket_type_id') || !$request->has('quantity')) {
            return back()->with('error', 'Pilih tiket terlebih dahulu.');
        }

        // Hitung jumlah tiket yang dibeli
        $totalQty = array_sum($request->quantity);

        // Validasi: minimal 1, maksimal 5 tiket
        if ($totalQty < 1) {
            return back()->with('error', 'Pilih minimal 1 tiket.');
        }

        if ($totalQty > 5) {
            return back()->with('error', 'Maksimal 5 tiket per pesanan.');
        }

        $total = 0;
        $items = [];

        foreach ($request->ticket_type_id as $i => $typeId) {

            $qty = (int)$request->quantity[$i];

            if ($qty <= 0) continue;

            $type = TicketType::find($typeId);

            if (!$type) {
                return back()->with('error', 'Tiket tidak ditemukan.');
            }

            $total += $type->price * $qty;

            $items[] = [
                'ticket_type_id' => $type->id,
                'quantity'       => $qty,
                'price'          => $type->price,
            ];
        }

        if (empty($items)) {
            return back()->with('error', 'Tidak ada tiket yang dipilih.');
        }

        $order = Order::create([
            'user_id'      => Auth::id(),
            'concert_id'   => $concert->id,
            'buyer_name'   => Auth::user()->name,
            'buyer_email'  => Auth::user()->email,
            'total_amount' => $total,
            'status'       => 'pending',
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id'       => $order->id,
                'ticket_type_id' => $item['ticket_type_id'],
                'quantity'       => $item['quantity'],
                'price'          => $item['price'],
            ]);

            TicketType::where('id', $item['ticket_type_id'])
                ->increment('sold', $item['quantity']);
        }

        return redirect()->route('purchase.detail', $order->id);
    }
    public function detail(Order $order)
    {
        return view('purchase.detail', [
            'order' => $order,
            'concert' => $order->concert
        ]);
    }

    public function payment(Order $order)
    {
        // Payment step view — select payment method and show order summary
        return view('purchase.payment', [
            'order' => $order,
            'concert' => $order->concert
        ]);
    }

    public function pay(Request $request, Order $order)
    {
        $method = $request->input('payment_method');

        // Basic validation
        if (!$method) {
            return back()->with('error', 'Pilih metode pembayaran terlebih dahulu.');
        }

        // Save chosen payment method and mark as processing
        $order->payment_method = $method;
        $order->status = 'processing';
        $order->save();

        // Redirect to confirmation page
        return redirect()->route('purchase.confirmation', $order->id);
    }

    public function confirmation(Order $order)
    {
        // Show payment confirmation page with timer and instructions
        return view('purchase.confirmation', [
            'order' => $order,
            'concert' => $order->concert
        ]);
    }

    public function processDetail(Request $request, Order $order)
    {
        $order->update([
            'buyer_name' => $request->name,
            'buyer_email' => $request->email,
            'buyer_phone' => $request->phone,
            'identity_type' => $request->identity_type,
            'identity_number' => $request->identity_number,
        ]);

        return redirect()->route('purchase.payment', $order->id);
    }
}
