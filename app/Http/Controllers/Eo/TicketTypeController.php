<?php

namespace App\Http\Controllers\Eo;

use App\Http\Controllers\Controller;
use App\Models\Concert;
use App\Models\TicketType;
use Illuminate\Http\Request;

class TicketTypeController extends Controller
{
    public function index($concertId)
    {
        $concert = Concert::where('organizer_id', auth()->user()->organizer->id)
            ->findOrFail($concertId);

        $tickets = $concert->ticketTypes;

        return view('eo.tickets.index', compact('concert', 'tickets'));
    }

    public function create($concertId)
    {
        $concert = Concert::where('organizer_id', auth()->user()->organizer->id)
            ->findOrFail($concertId);

        return view('eo.tickets.create', compact('concert'));
    }

    public function store(Request $request, $concertId)
    {
        $concert = Concert::where('organizer_id', auth()->user()->organizer->id)
            ->findOrFail($concertId);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quota' => 'required|numeric|min:1'
        ]);

        TicketType::create([
            'concert_id' => $concert->id,
            'name' => $request->name,
            'price' => $request->price,
            'quota' => $request->quota,
            'sold' => 0
        ]);

        return redirect()->route('eo.concerts.tickets.index', $concert->id)
            ->with('success', 'Tipe tiket berhasil ditambahkan!');
    }

    public function edit(TicketType $ticket)
    {
        $concert = Concert::where('organizer_id', auth()->user()->organizer->id)
            ->findOrFail($ticket->concert_id);

        return view('eo.tickets.edit', compact('ticket', 'concert'));
    }

    public function update(Request $request, TicketType $ticket)
    {
        $concert = Concert::where('organizer_id', auth()->user()->organizer->id)
            ->findOrFail($ticket->concert_id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quota' => 'required|numeric|min:1'
        ]);

        $ticket->update($request->only(['name', 'price', 'quota']));

        return redirect()->route('eo.concerts.tickets.index', $concert->id)
            ->with('success', 'Tipe tiket berhasil diperbarui!');
    }

    public function destroy(TicketType $ticket)
    {
        $concert = Concert::where('organizer_id', auth()->user()->organizer->id)
            ->findOrFail($ticket->concert_id);

        $ticket->delete();

        return redirect()->route('eo.concerts.tickets.index', $concert->id)
            ->with('success', 'Tipe tiket berhasil dihapus!');
    }
}
