<?php

namespace App\Http\Controllers\Eo;

use App\Http\Controllers\Controller;
use App\Models\Concert;
use Illuminate\Http\Request;

class EoConcertController extends Controller
{
    /**
     * List Concert EO
     */
    public function index()
    {
        $concerts = Concert::where('organizer_id', auth()->id())->get();
        return view('eo.concerts.index', compact('concerts'));
    }

    /**
     * SHOW FORM Create Concert (STEP 4A)
     */
    public function create()
    {
        return view('eo.concerts.create');
    }

    /**
     * STORE concert baru (STEP 4B)
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'location' => 'required',
            'date' => 'required|date',
            'image_url' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload Poster Konser
        $imagePath = $request->file('image_url')->store('concerts', 'public');

        // Simpan data konser
        $concert = Concert::create([
            'title' => $request->title,
            'location' => $request->location,
            'date' => $request->date,
            'price' => 0, // Harga asli nanti dari Ticket Types
            'image_url' => $imagePath,
            'organizer_id' => auth()->id(), // EO yang login
            'status' => 'draft',
        ]);

        return redirect()->route('concerts.edit', $concert->id)
            ->with('success', 'Konser berhasil dibuat! Tambahkan tipe tiket.');
    }

    /**
     * FORM EDIT Concert
     */
    public function edit($id)
    {
        $concert = Concert::where('organizer_id', auth()->id())->findOrFail($id);
        return view('eo.concerts.edit', compact('concert'));
    }

    /**
     * UPDATE Concert
     */
    public function update(Request $request, $id)
    {
        $concert = Concert::where('organizer_id', auth()->id())->findOrFail($id);

        $concert->update($request->only(['title', 'location', 'date']));

        return back()->with('success', 'Concert updated!');
    }

    /**
     * DELETE Concert
     */
    public function destroy($id)
    {
        $concert = Concert::where('organizer_id', auth()->id())->findOrFail($id);
        $concert->delete();

        return redirect()->route('concerts.index')->with('success', 'Concert deleted!');
    }
}
