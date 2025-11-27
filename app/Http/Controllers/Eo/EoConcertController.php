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
            'image_url'   => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string', // ✔ description
        ]);

        $organizer = auth()->user()->organizer
            ?? Organizer::create([
                'user_id' => auth()->id(),
                'organization_name' => auth()->user()->name,
            ]);

        $imagePath = $request->file('image_url')->store('concerts', 'public');

        $concert = Concert::create([
            'title'           => $request->title,
            'location'        => $request->location,
            'date'            => $request->date,
            'price'           => 0,
            'image_url'       => $imagePath,
            'selling_status'  => 'coming_soon',
            'approval_status' => 'pending', // ✔ dikirim ke admin untuk approval
            'organizer_id'    => $organizer->id,
            'description'     => $request->description, // ✔ simpan
        ]);

        return redirect()->route('eo.concerts.edit', $concert->id)
            ->with('success', 'Konser berhasil dibuat! Tambahkan tipe tiket.');
    }

    public function edit($id)
    {
        $organizerId = auth()->user()->organizer->id;
        $concert = Concert::where('organizer_id', $organizerId)->findOrFail($id);

        return view('eo.concerts.edit', compact('concert'));
    }

    public function update(Request $request, $id)
    {
        $organizerId = auth()->user()->organizer->id;
        $concert = Concert::where('organizer_id', $organizerId)->findOrFail($id);

        $request->validate([
            'title'       => 'required|max:255',
            'location'    => 'required',
            'date'        => 'required|date',
            'description' => 'nullable|string', // ✔ tambahkan validasi
        ]);

        $concert->update([
            'title'       => $request->title,
            'location'    => $request->location,
            'date'        => $request->date,
            'description' => $request->description, // ✔ bisa diupdate juga
        ]);

        return back()->with('success', 'Konser berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $organizerId = auth()->user()->organizer->id;
        $concert = Concert::where('organizer_id', $organizerId)->findOrFail($id);

        $concert->delete();

        return redirect()->route('eo.concerts.index')->with('success', 'Concert deleted!');
    }
}
