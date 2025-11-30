<?php

namespace App\Http\Controllers;

use App\Models\Organizer;
use Illuminate\Http\Request;

class OrganizerController extends Controller
{
    /**
     * Display the organizer profile with their events.
     */
    public function show(Organizer $organizer)
    {
        // eager load concerts relation
        $organizer->load(['concerts']);

        return view('organizers.show', compact('organizer'));
    }
}
