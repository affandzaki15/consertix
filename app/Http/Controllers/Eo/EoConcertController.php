<?php

namespace App\Http\Controllers\Eo;

use App\Http\Controllers\Controller;
use App\Models\Concert;
use App\Models\Organizer;
use Illuminate\Http\Request;

class EoConcertController extends Controller
{
    /**
     * List Concert EO
     */
    public function index()
    {
        $organizerId = auth()->user()->organizer->id;

        $concerts = Concert::where('organizer_id', $organizerId)->get();

        return view('eo.concerts.index', compact('concerts'));
    }

    /**
     * SHOW FORM Create Concert
     */
    public function create()
    {
        return view('eo.concerts.create');
    }

    /**
     * STORE concert baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'     => 'required|max:255',
            'location'  => 'required',
            'date'      => 'required|date',
            'image_url' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Pastikan EO punya organizer (kalau belum, buat)
        $organizer = auth()->user()->organizer
            ?? Organizer::firstOrCreate(
                ['user_id' => auth()->id()],
                ['organization_name' => auth()->user()->name]
            );

        // Upload gambar
        $imagePath = $request->file('image_url')->store('concerts', 'public');

        // Simpan concert
        // Simpan concert (awal = coming_soon)
        $concert = Concert::create([
            'title'        => $request->title,
            'location'     => $request->location,
            'date'         => $request->date,
            'price'        => 0,
            'image_url'    => $imagePath,
            'status'       => 'coming_soon', // 👈 ganti
            'organizer_id' => $organizer->id,
        ]);

        return redirect()
            ->route('eo.concerts.edit', $concert->id)
            ->with('success', 'Konser berhasil dibuat! Tambahkan tipe tiket.');
    }

    public function edit($id)
    {
        $concert = Concert::where('organizer_id', auth()->user()->organizer()->first()->id)
            ->findOrFail($id);

        return view('eo.concerts.edit', compact('concert'));
    }

    public function update(Request $request, $id)
    {
        $concert = Concert::where('organizer_id', auth()->user()->organizer()->first()->id)
            ->findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'location' => 'required',
            'date' => 'required|date',
        ]);

        $concert->update($request->only('title', 'location', 'date'));

        return back()->with('success', 'Konser berhasil diperbarui!');
    }


    /**
     * DELETE Concert
     */
    public function destroy($id)
    {
        $organizerId = auth()->user()->organizer->id;

        $concert = Concert::where('organizer_id', $organizerId)->findOrFail($id);
        $concert->delete();

        return redirect()->route('concerts.index')->with('success', 'Concert deleted!');
    }
}
