<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketType;
use Illuminate\Http\Request;

class TicketsController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        // contoh cari lewat TicketType atau relasi ticket jika ada
        $tickets = TicketType::when($q, fn($b) => $b->where('name', 'like', "%{$q}%"))->paginate(25);
        return view('admin.tickets.index', compact('tickets', 'q'));
    }
}