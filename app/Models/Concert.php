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
        return $this->hasMany(TicketType::class, 'concert_id');
    }
    public function organizer()
    {
        return $this->belongsTo(\App\Models\Organizer::class, 'organizer_id');
    }
}
