<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ConcertAdminAction; // 👈 Tambahkan ini

class Concert extends Model
{
    protected $fillable = [
        'title',
        'location',
        'date',
        'time',
        'price',
        'image_url',
        'selling_status',
        'approval_status',
        'organizer_id',
        'description',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
        'price' => 'integer',
    ];

    // Relasi ke Organizer
    public function organizer()
    {
        return $this->belongsTo(Organizer::class, 'organizer_id');
    }

    // Relasi ke Tiket
    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class);
    }

    // ✅ Tambahkan relasi adminActions
    public function adminActions()
    {
        return $this->hasMany(ConcertAdminAction::class);
    }

    public function updateSellingStatus()
    {
        $totalQuota = $this->ticketTypes()->sum('quota');
        $sold = $this->ticketTypes()->sum('sold');

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

    public function getSellingStatusLabelAttribute()
    {
        return match ($this->selling_status) {
            'coming_soon' => 'Coming Soon',
            'available' => 'Tiket Tersedia',
            'sold_out' => 'Sold Out',
            default => 'Unknown',
        };
    }

    public function updatePriceFromTickets()
    {
        $lowestPrice = $this->ticketTypes()->min('price');
        $this->price = $lowestPrice ?? 0;
        $this->save();
    }
}