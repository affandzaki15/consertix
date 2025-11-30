<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConcertAdminAction extends Model
{
    protected $fillable = [
        'concert_id',
        'admin_id',
        'action',
        'note'
    ];

    public function admin()
    {
        return $this->belongsTo(\App\Models\User::class, 'admin_id');
    }

    public function concert()
    {
        return $this->belongsTo(Concert::class);
    }
}