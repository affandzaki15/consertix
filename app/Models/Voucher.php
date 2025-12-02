<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{
    protected $fillable = [
        'organizer_id',
        'code',
        'description',
        'discount_type',
        'discount_value',
        'usage_limit',
        'usage_count',
        'max_per_user',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',

    ];

    // Accessor untuk memastikan status aktif sesuai tanggal
    public function getIsActiveAttribute($value)
    {
        if ($this->valid_until && now()->gt($this->valid_until)) {
            return false;
        }
        return $value;
    }

    // Relasi
    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }

    public function usages()
    {
        return $this->hasMany(VoucherUsage::class);
    }

    // Helper methods
    public function isValid()
    {
        $now = Carbon::now();

        if (!$this->is_active) return false;

        if ($this->valid_from && $now->lt($this->valid_from)) return false;
        if ($this->valid_until && $now->gt($this->valid_until)) return false;
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) return false;

        return true;
    }

    public function userCanUse($userId)
    {
        if (!$this->isValid()) return false;

        $userUsageCount = $this->usages()
            ->where('user_id', $userId)
            ->count();

        return $userUsageCount < $this->max_per_user;
    }

    public function calculateDiscount($amount)
    {
        if ($this->discount_type === 'percentage') {
            return intval($amount * $this->discount_value / 100);
        }
        return min($this->discount_value, $amount);
    }
}
