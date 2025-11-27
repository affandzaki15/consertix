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
    public function updateStatus()
    {
        $totalTickets = $this->ticketTypes()->sum('quota');
        $soldTickets  = $this->ticketTypes()->sum('sold');

        if ($totalTickets == 0) {
            $this->status = 'coming_soon';
        } elseif ($soldTickets >= $totalTickets) {
            $this->status = 'sold_out';
        } else {
            $this->status = 'available';
        }

        $this->save();
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'coming_soon' => 'Coming Soon',
            'available' => 'Tiket Tersedia',
            'sold_out' => 'Sold Out',
            default => ucfirst($this->status),
        };
    }

    public function updatePriceFromTickets()
{
    $lowestPrice = $this->ticketTypes()->min('price');

    // Kalau belum ada tiket → set price tetap 0
    $this->price = $lowestPrice ?? 0;
    $this->save();
}




    public function organizer()
    {
        return $this->belongsTo(\App\Models\Organizer::class, 'organizer_id');
    }
}
