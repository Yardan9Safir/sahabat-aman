<?php

namespace App\Policies;

use App\Models\Quote;
use App\Models\User;

class QuotePolicy
{
    /**
     * Guru BK boleh mengelola seluruh quotes. Selain itu hanya quote sendiri.
     */
    public function before(User $user, string $ability): ?bool
    {
        return $user->isGuru() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Quote $quote): bool
    {
        return $quote->id_pembuat === $user->id;
    }

    public function delete(User $user, Quote $quote): bool
    {
        return $quote->id_pembuat === $user->id;
    }

    /**
     * Memilih kata-kata hari ini hanya untuk guru.
     */
    public function setDaily(User $user): bool
    {
        return $user->isGuru();
    }
}
