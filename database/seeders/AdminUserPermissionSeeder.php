<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AdminUserPermissionSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | CLEAR PERMISSION CACHE
        |--------------------------------------------------------------------------
        */

        app(
            PermissionRegistrar::class
        )->forgetCachedPermissions();


        /*
        |--------------------------------------------------------------------------
        | DAFTAR PERMISSION BALIHIKING
        |--------------------------------------------------------------------------
        */

        $permissions = [

            /*
            |--------------------------------------------------------------------------
            | DASHBOARD
            |--------------------------------------------------------------------------
            */

            'dashboard.view',


            /*
            |--------------------------------------------------------------------------
            | GUNUNG
            |--------------------------------------------------------------------------
            */

            'mountains.view',
            'mountains.create',
            'mountains.update',
            'mountains.delete',


            /*
            |--------------------------------------------------------------------------
            | JALUR PENDAKIAN
            |--------------------------------------------------------------------------
            */

            'trails.view',
            'trails.create',
            'trails.update',
            'trails.delete',


            /*
            |--------------------------------------------------------------------------
            | CHECKPOINT
            |--------------------------------------------------------------------------
            */

            'checkpoints.view',
            'checkpoints.create',
            'checkpoints.update',
            'checkpoints.delete',


            /*
            |--------------------------------------------------------------------------
            | LAPORAN KONDISI JALUR
            |--------------------------------------------------------------------------
            */

            'trail_reports.view',
            'trail_reports.create',
            'trail_reports.update',
            'trail_reports.delete',


            /*
            |--------------------------------------------------------------------------
            | AKTIVITAS PENDAKI
            |--------------------------------------------------------------------------
            */

            'user_routes.view',
            'user_routes.complete',


            /*
            |--------------------------------------------------------------------------
            | LIVE TRACKING
            |--------------------------------------------------------------------------
            */

            'live_tracking.view',


            /*
            |--------------------------------------------------------------------------
            | PANDUAN & KEAMANAN
            |--------------------------------------------------------------------------
            */

            'trail_guides.view',
            'trail_guides.create',
            'trail_guides.update',
            'trail_guides.delete',


            /*
            |--------------------------------------------------------------------------
            | USER MANAGEMENT
            |--------------------------------------------------------------------------
            */

            'users.view',
            'users.create',
            'users.update',
            'users.delete',


            /*
            |--------------------------------------------------------------------------
            | ROLE & PERMISSION
            |--------------------------------------------------------------------------
            */

            'roles.manage',


            /*
            |--------------------------------------------------------------------------
            | PENUGASAN LOKASI
            |--------------------------------------------------------------------------
            */

            'location_assignments.manage',

        ];


        /*
        |--------------------------------------------------------------------------
        | CREATE / UPDATE PERMISSIONS
        |--------------------------------------------------------------------------
        */

        foreach ($permissions as $permissionName) {

            Permission::firstOrCreate([
                'name' =>
                    $permissionName,

                'guard_name' =>
                    'web',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE ADMIN ROLE
        |--------------------------------------------------------------------------
        */

        $adminRole =
            Role::firstOrCreate([
                'name' =>
                    'admin',

                'guard_name' =>
                    'web',
            ]);


        /*
        |--------------------------------------------------------------------------
        | ADMIN MENDAPAT SELURUH PERMISSION
        |--------------------------------------------------------------------------
        */

        $adminRole->syncPermissions(
            Permission::query()
                ->where(
                    'guard_name',
                    'web'
                )
                ->get()
        );


        /*
        |--------------------------------------------------------------------------
        | CARI USER ADMIN
        |--------------------------------------------------------------------------
        */

        $adminUser =
            User::query()
                ->where(
                    'email',
                    'admin@gmail.com'
                )
                ->first();


        if (! $adminUser) {

            throw new RuntimeException(
                'User admin@gmail.com tidak ditemukan. Pastikan akun tersebut sudah ada pada tabel users.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | SET ROLE ADMIN
        |--------------------------------------------------------------------------
        |
        | syncRoles akan memastikan akun tersebut hanya memiliki role admin.
        |
        */

        $adminUser->syncRoles([
            $adminRole,
        ]);


        /*
        |--------------------------------------------------------------------------
        | CLEAR CACHE LAGI
        |--------------------------------------------------------------------------
        */

        app(
            PermissionRegistrar::class
        )->forgetCachedPermissions();


        /*
        |--------------------------------------------------------------------------
        | OUTPUT
        |--------------------------------------------------------------------------
        */

        $this->command?->info(
            'Role admin berhasil diberikan kepada admin@gmail.com.'
        );

        $this->command?->info(
            'Total permission admin: '
            .
            $adminRole->permissions()->count()
        );
    }
}