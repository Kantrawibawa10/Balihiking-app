<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PendakiRoleSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | CLEAR CACHE
        |--------------------------------------------------------------------------
        */

        app(
            PermissionRegistrar::class
        )->forgetCachedPermissions();


        /*
        |--------------------------------------------------------------------------
        | PERMISSION PENDAKI
        |--------------------------------------------------------------------------
        |
        | Ini permission untuk aplikasi frontend / PWA.
        | BUKAN permission untuk Filament Admin.
        |
        */

        $permissions = [

            /*
            |--------------------------------------------------------------------------
            | DASHBOARD PENDAKI
            |--------------------------------------------------------------------------
            */

            'pendaki.dashboard.view',


            /*
            |--------------------------------------------------------------------------
            | DATA GUNUNG
            |--------------------------------------------------------------------------
            */

            'pendaki.mountains.view',


            /*
            |--------------------------------------------------------------------------
            | JALUR PENDAKIAN
            |--------------------------------------------------------------------------
            */

            'pendaki.trails.view',


            /*
            |--------------------------------------------------------------------------
            | SIMAKSI
            |--------------------------------------------------------------------------
            */

            'pendaki.simaksi.view',
            'pendaki.simaksi.create',


            /*
            |--------------------------------------------------------------------------
            | LIVE TRACKING
            |--------------------------------------------------------------------------
            */

            'pendaki.live_tracking.view',
            'pendaki.live_tracking.start',
            'pendaki.live_tracking.complete',


            /*
            |--------------------------------------------------------------------------
            | SOS
            |--------------------------------------------------------------------------
            */

            'pendaki.sos.send',


            /*
            |--------------------------------------------------------------------------
            | RIWAYAT
            |--------------------------------------------------------------------------
            */

            'pendaki.history.view',


            /*
            |--------------------------------------------------------------------------
            | PROFIL
            |--------------------------------------------------------------------------
            */

            'pendaki.profile.view',
            'pendaki.profile.update',

        ];


        /*
        |--------------------------------------------------------------------------
        | CREATE PERMISSIONS
        |--------------------------------------------------------------------------
        */

        foreach (
            $permissions as $permissionName
        ) {

            Permission::firstOrCreate([
                'name' =>
                    $permissionName,

                'guard_name' =>
                    'web',
            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | CREATE ROLE PENDAKI
        |--------------------------------------------------------------------------
        */

        $pendakiRole =
            Role::firstOrCreate([
                'name' =>
                    'pendaki',

                'guard_name' =>
                    'web',
            ]);


        /*
        |--------------------------------------------------------------------------
        | ASSIGN PERMISSIONS
        |--------------------------------------------------------------------------
        */

        $pendakiRole->syncPermissions(
            $permissions
        );


        /*
        |--------------------------------------------------------------------------
        | CLEAR CACHE
        |--------------------------------------------------------------------------
        */

        app(
            PermissionRegistrar::class
        )->forgetCachedPermissions();


        $this->command?->info(
            'Role pendaki berhasil dibuat.'
        );

        $this->command?->info(
            'Total permission pendaki: '
            .
            $pendakiRole
                ->permissions()
                ->count()
        );
    }
}