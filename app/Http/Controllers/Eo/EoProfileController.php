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

        return view('eo.profile.edit', compact('organizer'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'organization_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'url_logo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $organizer = auth()->user()->organizer;

        if (!$organizer) {
            $organizer = Organizer::create([
                'user_id' => auth()->id(),
            ]);
        }

        $data = $request->only(['organization_name', 'phone', 'address']);

        if ($request->hasFile('url_logo')) {
            $data['url_logo'] = $request->file('url_logo')->store('organizers', 'public');
        }

        $organizer->update($data);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
