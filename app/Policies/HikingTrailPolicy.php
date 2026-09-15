<?php

namespace App\Policies;

use App\Models\HikingTrail;
use App\Models\User;

class HikingTrailPolicy
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
        HikingTrail $trail
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
        HikingTrail $trail
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

    public function delete(
        User $user,
        HikingTrail $trail
    ): bool {
        return $user->isAdmin();
    }

    public function deleteAny(
        User $user
    ): bool {
        return $user->isAdmin();
    }
}