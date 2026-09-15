<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserRoute;

class UserRoutePolicy
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
        UserRoute $route
    ): bool {
        return $this->viewAny(
            $user
        );
    }

    public function create(
        User $user
    ): bool {
        return false;
    }

    public function update(
        User $user,
        UserRoute $route
    ): bool {
        return false;
    }

    public function delete(
        User $user,
        UserRoute $route
    ): bool {
        return false;
    }

    public function deleteAny(
        User $user
    ): bool {
        return false;
    }
}