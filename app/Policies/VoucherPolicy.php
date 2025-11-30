<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Voucher;

class VoucherPolicy
{
    /**
     * Determine if the user can view the voucher.
     */
    public function view(User $user, Voucher $voucher)
    {
        return $user->organizer_id === $voucher->organizer_id;
    }

    /**
     * Determine if the user can create vouchers.
     */
    public function create(User $user)
    {
        return $user->organizer_id !== null;
    }

    /**
     * Determine if the user can update the voucher.
     */
    public function update(User $user, Voucher $voucher)
    {
        return $user->organizer_id === $voucher->organizer_id;
    }

    /**
     * Determine if the user can delete the voucher.
     */
    public function delete(User $user, Voucher $voucher)
    {
        return $user->organizer_id === $voucher->organizer_id;
    }
}
