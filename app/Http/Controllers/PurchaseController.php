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
        // Add order items to cart for easy resumption
        $cart = session()->get('cart', []);
        
        if (!isset($cart[$order->concert_id])) {
            $cart[$order->concert_id] = [];
        }

        foreach ($order->items as $item) {
            if (isset($cart[$order->concert_id][$item->ticket_type_id])) {
                $cart[$order->concert_id][$item->ticket_type_id] += $item->quantity;
            } else {
                $cart[$order->concert_id][$item->ticket_type_id] = $item->quantity;
            }
        }

        session()->put('cart', $cart);

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

    // ===== CART MANAGEMENT =====
    
    public function cart()
    {
        $cart = session()->get('cart', []);
        $cartItems = [];
        $total = 0;

        foreach ($cart as $concertId => $tickets) {
            $concert = Concert::find($concertId);
            if ($concert) {
                foreach ($tickets as $ticketTypeId => $qty) {
                    $ticketType = TicketType::find($ticketTypeId);
                    if ($ticketType) {
                        $itemTotal = $ticketType->price * $qty;
                        $cartItems[] = [
                            'concert' => $concert,
                            'ticketType' => $ticketType,
                            'quantity' => $qty,
                            'price' => $ticketType->price,
                            'total' => $itemTotal,
                            'concertId' => $concertId,
                            'ticketTypeId' => $ticketTypeId,
                        ];
                        $total += $itemTotal;
                    }
                }
            }
        }

        return view('cart', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    public function cartAdd(Request $request)
    {
        $concert_id = $request->input('concert_id');
        $ticket_type_id = $request->input('ticket_type_id');
        $quantity = (int) $request->input('quantity', 1);

        // Get or initialize cart
        $cart = session()->get('cart', []);

        // Add to cart
        if (!isset($cart[$concert_id])) {
            $cart[$concert_id] = [];
        }

        if (isset($cart[$concert_id][$ticket_type_id])) {
            $cart[$concert_id][$ticket_type_id] += $quantity;
        } else {
            $cart[$concert_id][$ticket_type_id] = $quantity;
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Added to cart');
    }

    public function cartRemove($ticket_type_id)
    {
        $cart = session()->get('cart', []);

        foreach ($cart as $concertId => &$tickets) {
            if (isset($tickets[$ticket_type_id])) {
                unset($tickets[$ticket_type_id]);
                if (empty($tickets)) {
                    unset($cart[$concertId]);
                }
            }
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Removed from cart');
    }

    public function cartClear()
    {
        session()->forget('cart');
        return back()->with('success', 'Cart cleared');
    }

    /**
     * Mark an order as paid (called from confirmation modal)
     */
    public function completePayment(Request $request, Order $order)
    {
        // Ensure the current user owns the order
        if ($order->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $order->status = 'paid';
        // optional: set paid timestamp if you have a 'paid_at' column
        // e.g. $order->paid_at = now();
        $order->save();

        return response()->json([
            'success' => true,
            'status' => $order->status,
            'updated_at' => $order->updated_at->toDateTimeString(),
        ]);
    }
}
