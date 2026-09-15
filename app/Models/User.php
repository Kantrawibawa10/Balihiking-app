<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'password',
    'role',
    'google_id',
])]
#[Hidden([
    'password',
    'remember_token',
])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | FILAMENT ACCESS
    |--------------------------------------------------------------------------
    |
    | Hanya:
    |
    | - admin
    | - pengelola_jalur
    |
    | yang diperbolehkan masuk ke panel Filament /admin.
    |
    | Pendaki dengan role "user" tidak diperbolehkan.
    |
    */

    public function canAccessPanel(Panel $panel): bool
    {
        /*
         * Pastikan aturan ini hanya berlaku
         * untuk panel dengan ID "admin".
         */
        if ($panel->getId() !== 'admin') {
            return false;
        }

        return in_array(
            $this->role,
            [
                'admin',
                'pengelola_jalur',
            ],
            true
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE HELPERS
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTrailManager(): bool
    {
        return $this->role === 'pengelola_jalur';
    }

    public function isPendaki(): bool
    {
        return $this->role === 'user';
    }

    /*
    |--------------------------------------------------------------------------
    | USER ROUTES / AKTIVITAS PENDAKIAN
    |--------------------------------------------------------------------------
    */

    public function userRoutes(): HasMany
    {
        return $this->hasMany(
            UserRoute::class,
            'user_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | USER LOCATIONS / LIVE TRACKING
    |--------------------------------------------------------------------------
    */

    public function locations(): HasMany
    {
        return $this->hasMany(
            UserLocation::class,
            'user_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TRAIL REPORTS / FEEDBACK KONDISI JALUR
    |--------------------------------------------------------------------------
    */

    public function trailReports(): HasMany
    {
        return $this->hasMany(
            TrailReport::class,
            'user_id'
        );
    }
}