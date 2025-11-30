<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
            ->latest()
            ->first();

        // Jika ada order yang belum selesai (pending/processing), hapus otomatis
        // sehingga ketika user kembali ke halaman pemesanan, mereka mulai dari kosong.
        if ($existingOrder && in_array($existingOrder->status, ['pending', 'processing'])) {
            // restore sold counts
            foreach ($existingOrder->items as $item) {
                TicketType::where('id', $item->ticket_type_id)
                    ->decrement('sold', $item->quantity);
            }

            // hapus order items lalu order
            $existingOrder->items()->delete();
            $existingOrder->delete();

            $existingOrder = null;
        }

        return view('purchase.show', [
            'concert'       => $concert,
            'ticketTypes'   => $concert->ticketTypes,
            'user'          => Auth::user(),
            'existingOrder' => $existingOrder
        ]);
    }

    public function store(Request $request, Concert $concert)
    {
        // DEBUG: Log request data
        Log::info('Purchase Store Request', [
            'ticket_type_id' => $request->ticket_type_id,
            'quantity' => $request->quantity,
        ]);

        // Cek apakah user sudah punya order untuk konser ini
        $existingOrder = Order::where('user_id', Auth::id())
            ->where('concert_id', $concert->id)
            ->latest()
            ->first();

        // If there's an existing order and it's not completed/paid, redirect user to continue it.
        // But allow creating a new order if the previous order is already 'paid'.
        if ($existingOrder && $existingOrder->status !== 'paid') {
            return redirect()->route('purchase.detail', $existingOrder->id)
                ->with('error', 'Anda memiliki pesanan tertunda. Silakan lanjutkan detail pesanan.');
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

    public function clearCurrent(Request $request)
    {
        $concert_id = $request->input('concert_id');
        
        // Delete all pending/processing orders for this concert
        Order::where('user_id', Auth::id())
            ->where('concert_id', $concert_id)
            ->whereIn('status', ['pending', 'processing'])
            ->each(function($order) {
                // Restore sold count
                foreach($order->items as $item) {
                    TicketType::where('id', $item->ticket_type_id)
                        ->decrement('sold', $item->quantity);
                }
                $order->items()->delete();
                $order->delete();
            });

        return back()->with('success', 'Pesanan sebelumnya telah dihapus. Silakan buat pesanan baru.');
    }

    public function detail(Order $order)
    {
        $order->load('items.ticketType');
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

        // Sync order items into session cart so user can see them in Cart
        // Reload order with items relation to ensure data is loaded
        $order->load('items');
        
        $cart = session()->get('cart', []);
        if (!isset($cart[$order->concert_id])) {
            $cart[$order->concert_id] = [];
        }

        foreach ($order->items as $item) {
            // Make sync idempotent: don't add repeatedly if the client already
            // synced the cart (eg. via iframe). Use max(existing, item.quantity)
            // so repeated visits won't increase the stored quantity.
            $existingQty = $cart[$order->concert_id][$item->ticket_type_id] ?? 0;
            $cart[$order->concert_id][$item->ticket_type_id] = max($existingQty, $item->quantity);
        }

        session()->put('cart', $cart);

        // Redirect to confirmation page
        return redirect()->route('purchase.confirmation', $order->id);
    }

    public function confirmation(Order $order)
    {
        // Only add order items to cart for resumption if the order is not already paid.
        // This prevents a paid order from being re-added to the session when the
        // user refreshes the confirmation page after payment.
        if (strtolower($order->status) !== 'paid') {
            $cart = session()->get('cart', []);

            if (!isset($cart[$order->concert_id])) {
                $cart[$order->concert_id] = [];
            }

            foreach ($order->items as $item) {
                // Idempotent sync for confirmation page as well — prefer max so
                // the cart doesn't grow on repeated views.
                $existingQty = $cart[$order->concert_id][$item->ticket_type_id] ?? 0;
                $cart[$order->concert_id][$item->ticket_type_id] = max($existingQty, $item->quantity);
            }

            session()->put('cart', $cart);
        }

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
                // find existing order for this user + concert (latest)
                $existingOrder = Order::where('user_id', Auth::id())
                    ->where('concert_id', $concertId)
                    ->latest()
                    ->first();
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
                            // Only link to an existing order if that order already contains this ticket type.
                            'orderId' => ($existingOrder && $existingOrder->items()->where('ticket_type_id', $ticketTypeId)->exists()) ? $existingOrder->id : null,
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

    /**
     * Create or resume an order from the cart for the given concert and
     * redirect user to the confirmation page.
     */
    public function checkoutFromCart(Concert $concert, $ticket_type_id = null)
    {
        $cart = session()->get('cart', []);

        $concertCart = $cart[$concert->id] ?? [];
        if (empty($concertCart)) {
            return back()->with('error', 'Tidak ada item di keranjang untuk konser ini.');
        }

        // If there's an existing non-paid order, reuse it
        $existingOrder = Order::where('user_id', Auth::id())
            ->where('concert_id', $concert->id)
            ->latest()
            ->first();

        if ($existingOrder && $existingOrder->status !== 'paid') {
            // If user requested a specific ticket type, only reuse existing order
            // when it already contains that ticket type. Otherwise create a new order
            // so the user can pay for the specific item they clicked.
            if ($ticket_type_id) {
                $hasType = $existingOrder->items->where('ticket_type_id', $ticket_type_id)->isNotEmpty();
                if ($hasType) {
                    return redirect()->route('purchase.confirmation', $existingOrder->id);
                }
                // else fall through and create a new order for the requested type
            } else {
                return redirect()->route('purchase.confirmation', $existingOrder->id);
            }
        }

        // Build items from cart data and create a new order.
        // If $ticket_type_id is provided, build only that item (if present in cart),
        // otherwise include all ticket types for this concert.
        $items = [];
        $total = 0;

        if ($ticket_type_id) {
            if (!isset($concertCart[$ticket_type_id])) {
                return back()->with('error', 'Tiket yang diminta tidak ada di keranjang.');
            }

            $type = TicketType::find($ticket_type_id);
            if ($type) {
                $qty = (int) $concertCart[$ticket_type_id];
                $items[] = [
                    'ticket_type_id' => $type->id,
                    'quantity' => $qty,
                    'price' => $type->price,
                ];
                $total += $type->price * $qty;
            }
        } else {
            foreach ($concertCart as $ticketTypeId => $qty) {
                $type = TicketType::find($ticketTypeId);
                if (!$type) continue;
                $items[] = [
                    'ticket_type_id' => $type->id,
                    'quantity' => (int) $qty,
                    'price' => $type->price,
                ];
                $total += $type->price * (int) $qty;
            }
        }

        if (empty($items)) {
            return back()->with('error', 'Tidak ada tiket valid di keranjang untuk konser ini.');
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'concert_id' => $concert->id,
            'buyer_name' => Auth::user()->name,
            'buyer_email' => Auth::user()->email,
            'total_amount' => $total,
            'status' => 'pending',
        ]);

        foreach ($items as $it) {
            OrderItem::create([
                'order_id' => $order->id,
                'ticket_type_id' => $it['ticket_type_id'],
                'quantity' => $it['quantity'],
                'price' => $it['price'],
            ]);

            TicketType::where('id', $it['ticket_type_id'])->increment('sold', $it['quantity']);
        }

        return redirect()->route('purchase.confirmation', $order->id);
    }

    public function cartAdd(Request $request)
    {
        $concert_id = $request->input('concert_id');

        // Support both single values and arrays from the form/js
        $ticket_type_ids = $request->input('ticket_type_id');
        $quantities = $request->input('quantity');

        if (!$concert_id) {
            return back()->with('error', 'Concert ID is required');
        }

        // Normalize to arrays
        if (!is_array($ticket_type_ids)) {
            $ticket_type_ids = $ticket_type_ids ? [$ticket_type_ids] : [];
        }

        if (!is_array($quantities)) {
            $quantities = $quantities ? [$quantities] : [];
        }

        // Get or initialize cart
        $cart = session()->get('cart', []);
        if (!isset($cart[$concert_id])) {
            $cart[$concert_id] = [];
        }

        // Iterate and add each ticket type
        foreach ($ticket_type_ids as $index => $ticket_type_id) {
            $qty = (int) ($quantities[$index] ?? 1);
            if ($qty <= 0) continue;

            if (isset($cart[$concert_id][$ticket_type_id])) {
                $cart[$concert_id][$ticket_type_id] += $qty;
            } else {
                $cart[$concert_id][$ticket_type_id] = $qty;
            }
        }

        session()->put('cart', $cart);

        // If request expects JSON (AJAX), return JSON with updated cart count
        if ($request->wantsJson() || $request->ajax()) {
            $cartCount = 0;
            foreach (session()->get('cart', []) as $c) {
                foreach ($c as $q) {
                    $cartCount += (int) $q;
                }
            }

            return response()->json([
                'success' => true,
                'cart_count' => $cartCount,
            ]);
        }

        return back()->with('success', 'Ditambahkan ke keranjang');
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
        $order->paid_at = now();
        $order->save();

        // Create Tickets for each order item (one ticket row per quantity)
        $order->load('items');

        try {
            // deferred imports
            \Illuminate\Support\Facades\Storage::makeDirectory('public/tickets');
        } catch (\Throwable $e) {
            // ignore if directory already exists or can't be created
        }

        // Check if tickets for this order already exist (avoid duplicates)
        $orderItemIds = $order->items->pluck('id')->toArray();
        $existingTickets = \App\Models\Ticket::whereIn('order_item_id', $orderItemIds)->get();

        $createdTicketIds = [];
        $createdTicketCodes = [];

        if ($existingTickets->isEmpty()) {
            // Create ticket rows (without QR url yet) and collect codes
            foreach ($order->items as $item) {
                for ($i = 0; $i < $item->quantity; $i++) {
                    $ticketCode = strtoupper('TKT-' . $order->reference_code . '-' . strtoupper(substr(uniqid(), -6)));

                    $ticket = \App\Models\Ticket::create([
                        'order_item_id' => $item->id,
                        'ticket_code' => $ticketCode,
                        'qr_code_url' => null,
                        'status' => 'active',
                        'issued_at' => now(),
                    ]);

                    $createdTicketIds[] = $ticket->id;
                    $createdTicketCodes[] = $ticketCode;
                }
            }
        } else {
            // Use existing tickets
            $createdTicketIds = $existingTickets->pluck('id')->toArray();
            $createdTicketCodes = $existingTickets->pluck('ticket_code')->toArray();
        }

        // Generate a single combined QR for the order containing all ticket codes
        if (!empty($createdTicketCodes)) {
            $qrPayload = json_encode([
                'order_id' => $order->id,
                'reference' => $order->reference_code,
                'tickets' => $createdTicketCodes,
            ]);

            try {
                $pngBinary = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(400)->generate($qrPayload);
                // Save to public/qrcodes instead of storage
                $filename = 'order-' . $order->reference_code . '.png';
                $publicPath = public_path('qrcodes/' . $filename);
                
                file_put_contents($publicPath, $pngBinary);
                $qrUrl = '/qrcodes/' . $filename;

                // update all tickets for this order to reference the single QR image
                \App\Models\Ticket::whereIn('id', $createdTicketIds)->update(['qr_code_url' => $qrUrl]);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Combined QR generation failed', ['error' => $e->getMessage()]);
            }
        }

        // Subtract only the purchased quantities for this order from the session cart
        $cart = session()->get('cart', []);
        if (isset($cart[$order->concert_id]) && is_array($cart[$order->concert_id])) {
            foreach ($order->items as $item) {
                $typeId = $item->ticket_type_id;
                $qtyToRemove = (int) $item->quantity;

                if (isset($cart[$order->concert_id][$typeId])) {
                    $currentQty = (int) $cart[$order->concert_id][$typeId];
                    $newQty = $currentQty - $qtyToRemove;
                    if ($newQty > 0) {
                        $cart[$order->concert_id][$typeId] = $newQty;
                    } else {
                        // remove this ticket type from cart completely
                        unset($cart[$order->concert_id][$typeId]);
                    }
                }
            }

            // If no ticket types remain for this concert, remove the concert entry
            if (empty($cart[$order->concert_id])) {
                unset($cart[$order->concert_id]);
            }

            session()->put('cart', $cart);
        }

        // Recalculate the remaining cart count to return to the client
        $cartCount = 0;
        foreach (session()->get('cart', []) as $c) {
            foreach ($c as $q) {
                $cartCount += (int) $q;
            }
        }

        return response()->json([
            'success' => true,
            'status' => $order->status,
            'updated_at' => $order->updated_at->toDateTimeString(),
            'cart_count' => $cartCount,
        ]);
    }

    public function cancelOrder(Request $request, Order $order)
    {
        // Ensure the current user owns the order
        if ($order->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Only allow cancellation of pending/processing orders, not paid ones
        if ($order->status === 'paid') {
            return response()->json(['error' => 'Cannot cancel paid orders'], 400);
        }

        // Restore sold counts for all items in this order
        foreach ($order->items as $item) {
            TicketType::where('id', $item->ticket_type_id)
                ->decrement('sold', $item->quantity);
        }

        // Remove order items
        $order->items()->delete();

        // Delete the order
        $order->delete();

        // Remove this order's items from session cart
        $cart = session()->get('cart', []);
        if (isset($cart[$order->concert_id])) {
            unset($cart[$order->concert_id]);
        }
        session()->put('cart', $cart);

        return response()->json(['success' => true, 'message' => 'Order cancelled successfully']);
    }
}
