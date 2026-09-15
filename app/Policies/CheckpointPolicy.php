<?php

namespace App\Policies;

use App\Models\Checkpoint;
use App\Models\User;

class CheckpointPolicy
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
        Checkpoint $checkpoint
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
        Checkpoint $checkpoint
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
        Checkpoint $checkpoint
    ): bool {
        return $user->isAdmin();
    }

    public function deleteAny(
        User $user
    ): bool {
        return $user->isAdmin();
    }
}