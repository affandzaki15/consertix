<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Concert;

class ConcertController extends Controller
{
    /**
     * Display a listing of concerts, optionally filtered by search query.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = Concert::query();

        if ($q) {
            $query->where(function ($builder) use ($q) {
                $builder->where('title', 'like', "%{$q}%")
                    ->orWhere('location', 'like', "%{$q}%");
            });
        }

        $concerts = $query->orderBy('date', 'desc')->paginate(12);

        return view('concerts', compact('concerts', 'q'));
    }

    /**
     * Return JSON search results for concerts (used by live search).
     */
    public function search(Request $request)
    {
        $query = $request->q;

        $concerts = Concert::where(function ($q) use ($query) {
            $q->where('title', 'like', '%' . $query . '%')
                ->orWhere('location', 'like', '%' . $query . '%');
        })
            ->with('organizer.user:id,name')
            ->select('id', 'title', 'location', 'date', 'image_url', 'organizer_id')
            ->get();


        // Perbaiki image path
        foreach ($concerts as $c) {
            $c->image_url = asset($c->image_url);
        }

        return response()->json($concerts);
    }


    /**
     * Display a single concert detail.
     */
    public function show(Concert $concert)
    {
        return view('concerts.show', compact('concert'));
    }
}
