<?php

namespace Database\Seeders;

use App\Models\Mountain;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PengelolaPendakianSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | CREATE USER
        |--------------------------------------------------------------------------
        */

        $user =
            User::updateOrCreate(
                [
                    'email' =>
                        'pengelola@gmail.com',
                ],
                [
                    'name' =>
                        'Pengelola Pendakian',

                    'password' =>
                        Hash::make(
                            'Pengelola123!'
                        ),
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | ROLE
        |--------------------------------------------------------------------------
        */

        $user->syncRoles([
            'pengelola_lokasi',
        ]);


        /*
        |--------------------------------------------------------------------------
        | CONTOH ASSIGN GUNUNG
        |--------------------------------------------------------------------------
        |
        | Default saya tidak memberikan semua gunung.
        |
        | Jika ada Gunung Agung, user ini diberikan akses ke sana.
        |--------------------------------------------------------------------------
        */

        $mountain =
            Mountain::query()
                ->where(
                    'name',
                    'Gunung Agung'
                )
                ->first();


        if (
            $mountain
        ) {
            $user
                ->mountains()
                ->sync([
                    $mountain->id,
                ]);
        }


        $this->command?->info(
            'User Pengelola Pendakian berhasil dibuat.'
        );


        $this->command?->warn(
            'Email: pengelola@gmail.com'
        );


        $this->command?->warn(
            'Password awal: Pengelola123! - ganti setelah testing.'
        );
    }
}