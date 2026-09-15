<?php

namespace App\Policies;

use App\Models\TrailGuide;
use App\Models\User;

class TrailGuidePolicy
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
        TrailGuide $guide
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
        TrailGuide $guide
    ): bool {
        return $user->isAdmin();
    }

    public function delete(
        User $user,
        TrailGuide $guide
    ): bool {
        return $user->isAdmin();
    }

    public function deleteAny(
        User $user
    ): bool {
        return $user->isAdmin();
    }
}