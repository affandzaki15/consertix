<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_name',
        'description',
        'phone',
        'address',
        'status',
        'url_logo',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function concerts()
    {
        return $this->hasMany(\App\Models\Concert::class, 'organizer_id');
    }

    public function vouchers()
    {
        return $this->hasMany(\App\Models\Voucher::class);
    }
}
