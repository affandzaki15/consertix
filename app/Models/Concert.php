<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Concert extends Model
{
    protected $fillable = [
        'title',
        'location',
        'date',
        'price',
        'image_url',
        'status',
        'organizer_id'
    ];

    protected $casts = [
        'date' => 'date',
        'price' => 'integer',
    ];

    // RELASI WAJIB — INI YANG HILANG (PENYEBAB ERROR)
   public function ticketTypes()
{
    return $this->hasMany(\App\Models\TicketType::class);
}

public function getTicketStatusAttribute()
{
    $totalTickets = $this->ticketTypes->sum('quota'); // pakai quota!
    $sold = $this->ticketTypes->sum('sold');

    if ($totalTickets == 0) {
        return 'coming_soon'; // Belum ada tiket
    }

    if ($sold >= $totalTickets) {
        return 'sold_out'; // Habis
    }

    return 'available'; // Masih ada tiket
}


    public function organizer()
    {
        return $this->belongsTo(\App\Models\Organizer::class, 'organizer_id');
    }
}
