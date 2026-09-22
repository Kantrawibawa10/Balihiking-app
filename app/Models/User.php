<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

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
    use HasFactory, HasRoles, Notifiable;

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

    /*
    |--------------------------------------------------------------------------
    | ROLE HELPERS
    |--------------------------------------------------------------------------
    */

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

    public function identityDocuments(): HasMany
    {
        return $this->hasMany(
            IdentityDocument::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MOUNTAIN ACCESS
    |--------------------------------------------------------------------------
    */

    public function canAccessMountain(
        int $mountainId
    ): bool {
        if ($this->isAdmin()) {
            return true;
        }

        return $this
            ->mountains()
            ->whereKey($mountainId)
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | MOUNTAIN ASSIGNMENT
    |--------------------------------------------------------------------------
    */

    public function mountains(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Mountain::class,
                'mountain_user'
            )
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | FILAMENT ADMIN ACCESS
    |--------------------------------------------------------------------------
    */

    public function canAccessPanel(
        Panel $panel
    ): bool {
        if (
            $panel->getId()
            !==
            'admin'
        ) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Admin selalu boleh masuk.
        |--------------------------------------------------------------------------
        */

        if (
            $this->hasRole(
                'admin'
            )
        ) {
            return true;
        }

        /*
        |--------------------------------------------------------------------------
        | Role lain wajib mempunyai permission ini.
        |--------------------------------------------------------------------------
        */

        return $this->can(
            'admin_panel.access'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->hasRole(
            'admin'
        );
    }

    public function isLocationManager(): bool
    {
        return $this->hasRole(
            'pengelola_lokasi'
        );
    }

    public function simaksis(): HasMany
    {
        return $this->hasMany(
            Simaksi::class
        );
    }
}
