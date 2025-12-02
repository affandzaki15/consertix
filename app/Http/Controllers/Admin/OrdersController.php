<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Organizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class OrdersController extends Controller
{
   public function index()
    {
        // Default admin orders page: show organizers list (so admin can pick organizer first)
        return $this->organizers();
    }

    /**
     * Show list of organizers for admin to choose from.
     */
    public function organizers()
    {
        // Only show organizers that have an associated user (real registered EOs)
        $organizers = Organizer::with(['user', 'concerts'])
            ->whereHas('user')
            ->orderBy('organization_name')
            ->get();

        // Add orders_count per organizer
        foreach ($organizers as $org) {
            $org->orders_count = Order::whereHas('concert', function ($q) use ($org) {
                $q->where('organizer_id', $org->id);
            })->count();
        }

        return view('admin.orders.organizers', compact('organizers'));
    }

    /**
     * Show orders filtered by organizer id
     */
    public function byOrganizer($organizerId)
    {
        $organizer = Organizer::findOrFail($organizerId);

        $orders = Order::whereHas('concert', function ($q) use ($organizerId) {
                        $q->where('organizer_id', $organizerId);
                    })
                    ->with('concert')
                    ->latest()
                    ->paginate(15);

        return view('admin.orders.index', compact('orders', 'organizer'));
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
public function concert()
{
    return $this->belongsTo(\App\Models\Concert::class);
}
}