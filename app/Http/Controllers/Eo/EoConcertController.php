<?php

namespace App\Http\Controllers\Eo;

use App\Http\Controllers\Controller;
use App\Models\Concert;
use App\Models\Organizer;
use Illuminate\Http\Request;

class EoConcertController extends Controller
{
    public function index()
    {
        $organizer = auth()->user()->organizer;

        if (!$organizer) {
            return redirect()->route('eo.dashboard')
                ->with('warning', 'Silakan lengkapi data organizer terlebih dahulu.');
        }

        $concerts = Concert::where('organizer_id', $organizer->id)->get();

        return view('eo.concerts.index', compact('concerts'));
    }

    public function create()
    {
        return view('eo.concerts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|max:255',
            'location'    => 'required',
            'date'        => 'required|date',
            'time'        => 'required',
            'image_url'   => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string',
        ]);

        $organizer = auth()->user()->organizer
            ?? Organizer::create([
                'user_id' => auth()->id(),
                'organization_name' => auth()->user()->name,
            ]);

        $imagePath = $request->file('image_url')->store('concerts', 'public');

        // ⭕ STATUS = Masih DRAFT, BELUM DI AJUKAN
        $concert = Concert::create([
            'title'           => $request->title,
            'location'        => $request->location,
            'date'            => $request->date,
            'time'            => $request->time,
            'price'           => 0,
            'image_url'       => $imagePath,
            'selling_status'  => 'coming_soon',
            'approval_status' => 'draft',
            'organizer_id'    => $organizer->id,
            'description'     => $request->description,
        ]);

        return redirect()->route('eo.concerts.tickets.index', $concert->id)
            ->with('success', 'Konser berhasil dibuat! Tambahkan tipe tiket.');
    }

    public function edit($id)
    {
        $concert = Concert::where('organizer_id', auth()->user()->organizer->id)
            ->findOrFail($id);

        return view('eo.concerts.edit', compact('concert'));
    }

    public function update(Request $request, $id)
    {
        $concert = Concert::where('organizer_id', auth()->user()->organizer->id)
            ->findOrFail($id);

        $request->validate([
            'title'       => 'required|max:255',
            'location'    => 'required',
            'date'        => 'required|date',
            'time'        => 'required',
            'description' => 'nullable|string',
        ]);

        $concert->update([
            'title'       => $request->title,
            'location'    => $request->location,
            'date'        => $request->date,
            'time'        => $request->time,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Konser berhasil diperbarui!');
    }

    public function review($id)
    {
        $concert = Concert::with('ticketTypes')
            ->where('organizer_id', auth()->user()->organizer->id)
            ->findOrFail($id);

        if ($concert->ticketTypes->count() < 1) {
            return back()->with('error', 'Tambahkan minimal 1 tiket sebelum submit!');
        }

        return view('eo.concerts.review', compact('concert'));
    }

    public function submitApproval($id)
    {
        $organizerId = auth()->user()->organizer->id;

        $concert = Concert::where('organizer_id', $organizerId)->findOrFail($id);
        $concert = Concert::where('organizer_id', auth()->user()->organizer->id)
            ->findOrFail($id);

        $concert->approval_status = 'pending';
        $concert->save();

        return redirect()->route('eo.dashboard')
            ->with('success', 'Konser berhasil diajukan ke admin!');

        // Cegah submit lagi kalau sudah pending / approved
        if (in_array($concert->approval_status, ['pending', 'approved'])) {
            return redirect()->route('eo.dashboard')
                ->with('warning', 'Konser ini sudah pernah diajukan!');
        }

        $concert->approval_status = 'pending';
        $concert->save();

        return redirect()->route('eo.dashboard')
            ->with('success', 'Konser berhasil dikirim ke admin untuk approval!');
    }


    public function destroy($id)
    {
        $concert = Concert::where('organizer_id', auth()->user()->organizer->id)
            ->findOrFail($id);

        $concert->delete();

        return redirect()->route('eo.dashboard')
            ->with('success', 'Konser berhasil dihapus!');
    }
}
