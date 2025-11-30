<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Concert extends Model
{
    protected $fillable = [
        'title',
        'location',
        'date',
        'time',
        'price',
        'image_url',
        'selling_status',   // coming_soon / available / sold_out
        'approval_status',  // pending / approved / rejected
        'organizer_id',
        'description',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
        'price' => 'integer',
    ];

    // Relasi TicketTypes
    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class);
    }

    // Relasi Organizer
    public function organizer()
    {
        return $this->belongsTo(Organizer::class, 'organizer_id');
    }

    /**
     * Update status penjualan berdasarkan tiket & approval admin
     */
    public function updateSellingStatus()
    {
        $totalQuota = $this->ticketTypes()->sum('quota');
        $sold = $this->ticketTypes()->sum('sold');

        // 🔥 Admin belum approve → tetap Coming Soon
        if ($this->approval_status !== 'approved') {
            $this->selling_status = 'coming_soon';
        } else {
            if ($totalQuota == 0) {
                $this->selling_status = 'coming_soon';
            } elseif ($sold >= $totalQuota) {
                $this->selling_status = 'sold_out';
            } else {
                $this->selling_status = 'available';
            }
        }

        $this->save();
    }

    /**
     * Label status untuk tampilan User
     */
    public function getSellingStatusLabelAttribute()
    {
        return match ($this->selling_status) {
            'coming_soon' => 'Coming Soon',
            'available' => 'Tiket Tersedia',
            'sold_out' => 'Sold Out',
            default => 'Unknown',
        };
    }

    /**
     * Update harga berdasarkan harga tiket termurah
     */
    public function updatePriceFromTickets()
    {
        $lowestPrice = $this->ticketTypes()->min('price');
        $this->price = $lowestPrice ?? 0;
        $this->save();
    }
}
