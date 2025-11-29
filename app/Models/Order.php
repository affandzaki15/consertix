<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id','concert_id','buyer_name','buyer_email','buyer_phone','total_amount','status','payment_method','identity_type','identity_number','reference_code','paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->reference_code) {
                // Generate unique reference code: TICKET-TIMESTAMP-RANDOM
                $model->reference_code = 'TKT-' . time() . '-' . strtoupper(substr(uniqid(), -6));
            }
        });
    }

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
        return $this->belongsTo(User::class);
    }
}

