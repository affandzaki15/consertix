<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id','concert_id','buyer_name','buyer_email','buyer_phone','total_amount','status','payment_method','identity_type','identity_number'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
      public function concert()
    {
        return $this->belongsTo(Concert::class);
    }
    
}

