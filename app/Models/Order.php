<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'concert_id',
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'voucher_id',
        'discount_amount',
        'total_amount',
        'status',
        'payment_method',
        'identity_type',
        'identity_number',
        'reference_code',
        'paid_at',
        'tickets_generated',
        'tickets_generated_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'tickets_generated_at' => 'datetime', // ✅ memungkinkan ->format() di Blade
        'tickets_generated' => 'boolean',     // opsional: otomatis konversi ke true/false
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->reference_code) {
                // Generate unique reference code: TKT-TIMESTAMP-RANDOM
                $model->reference_code = 'TKT-' . time() . '-' . strtoupper(substr(uniqid(), -6));
            }
        });
    }

    // Relasi
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function concert()
    {
        return $this->belongsTo(Concert::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}