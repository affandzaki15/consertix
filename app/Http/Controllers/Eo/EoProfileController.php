<?php

namespace App\Http\Controllers\Eo;

use App\Http\Controllers\Controller;
use App\Models\Organizer;
use Illuminate\Http\Request;

class EoProfileController extends Controller
{
    public function edit()
    {
        $organizer = auth()->user()->organizer;

        if (!$organizer) {
            $organizer = Organizer::create([
                'user_id' => auth()->id(),
                'organization_name' => auth()->user()->name, // Fallback default
            ]);
        }

        return view('eo.profile.edit', compact('organizer'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'organization_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'url_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $organizer = auth()->user()->organizer;

        if (!$organizer) {
            $organizer = Organizer::create([
                'user_id' => auth()->id(),
                'organization_name' => $request->organization_name,
            ]);
        }

        // Ambil data selain logo
        $data = $request->only(['organization_name', 'phone', 'address']);

        // Jika upload logo baru
        if ($request->hasFile('url_logo')) {
            // Hapus logo lama jika ada
            if ($organizer->url_logo && file_exists(public_path('foto/' . $organizer->url_logo))) {
                unlink(public_path('foto/' . $organizer->url_logo));
            }

            $filename = time() . '_' . $request->file('url_logo')->getClientOriginalName();
            $request->file('url_logo')->move(public_path('foto/organizers'), $filename);
            $data['url_logo'] = 'organizers' . DIRECTORY_SEPARATOR . $filename;
        }

        $organizer->update($data);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
