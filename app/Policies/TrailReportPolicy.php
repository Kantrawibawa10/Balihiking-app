<?php

namespace App\Policies;

use App\Models\TrailReport;
use App\Models\User;

class TrailReportPolicy
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
        TrailReport $report
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
        TrailReport $report
    ): bool {
        return false;
    }

    public function delete(
        User $user,
        TrailReport $report
    ): bool {
        return false;
    }

    public function deleteAny(
        User $user
    ): bool {
        return false;
    }
}