<?php

namespace App\Policies;

use App\Models\Mountain;
use App\Models\User;

class MountainPolicy
{
    public function viewAny(
        User $user
    ): bool {
        return in_array(
            $user->role,
            [
                'admin',
                'pengelola_jalur',
            ],
            true
        );
    }

    public function view(
        User $user,
        Mountain $mountain
    ): bool {
        return $this->viewAny(
            $user
        );
    }

    public function create(
        User $user
    ): bool {
        return $user->isAdmin();
    }

    public function update(
        User $user,
        Mountain $mountain
    ): bool {
        return $user->isAdmin();
    }

    public function delete(
        User $user,
        Mountain $mountain
    ): bool {
        return $user->isAdmin();
    }

    public function deleteAny(
        User $user
    ): bool {
        return $user->isAdmin();
    }
}