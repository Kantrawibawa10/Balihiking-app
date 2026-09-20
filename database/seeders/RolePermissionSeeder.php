<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
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
        | PERMISSIONS
        |--------------------------------------------------------------------------
        */

        $permissions = [

            /*
            |--------------------------------------------------------------------------
            | ADMIN PANEL
            |--------------------------------------------------------------------------
            */

            'admin_panel.access',

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
            | LAPORAN JALUR
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
            | PANDUAN
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
            | ROLE MANAGEMENT
            |--------------------------------------------------------------------------
            */

            'roles.manage',

            /*
            |--------------------------------------------------------------------------
            | ASSIGNMENT LOKASI
            |--------------------------------------------------------------------------
            */

            'location_assignments.manage',

            /*
            |--------------------------------------------------------------------------
            | PENDAKI FRONTEND
            |--------------------------------------------------------------------------
            */

            'pendaki.dashboard.view',

            'pendaki.mountains.view',

            'pendaki.trails.view',

            'pendaki.simaksi.view',
            'pendaki.simaksi.create',

            'pendaki.live_tracking.view',
            'pendaki.live_tracking.start',
            'pendaki.live_tracking.complete',

            'pendaki.sos.send',

            'pendaki.history.view',

            'pendaki.profile.view',
            'pendaki.profile.update',
        ];


        foreach (
            $permissions
            as
            $permissionName
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
        | ADMIN
        |--------------------------------------------------------------------------
        */

        $admin =
            Role::firstOrCreate([
                'name' =>
                    'admin',

                'guard_name' =>
                    'web',
            ]);


        $admin->syncPermissions(
            Permission::query()
                ->where(
                    'guard_name',
                    'web'
                )
                ->get()
        );


        /*
        |--------------------------------------------------------------------------
        | PENGELOLA PENDAKIAN
        |--------------------------------------------------------------------------
        */

        $manager =
            Role::firstOrCreate([
                'name' =>
                    'pengelola_lokasi',

                'guard_name' =>
                    'web',
            ]);


        $manager->syncPermissions([

            'admin_panel.access',

            'dashboard.view',

            /*
            |--------------------------------------------------------------------------
            | Gunung hanya lihat/edit lokasi sendiri.
            |--------------------------------------------------------------------------
            */

            'mountains.view',
            'mountains.update',

            /*
            |--------------------------------------------------------------------------
            | Jalur
            |--------------------------------------------------------------------------
            */

            'trails.view',
            'trails.create',
            'trails.update',
            'trails.delete',

            /*
            |--------------------------------------------------------------------------
            | Checkpoint
            |--------------------------------------------------------------------------
            */

            'checkpoints.view',
            'checkpoints.create',
            'checkpoints.update',
            'checkpoints.delete',

            /*
            |--------------------------------------------------------------------------
            | Laporan
            |--------------------------------------------------------------------------
            */

            'trail_reports.view',
            'trail_reports.update',

            /*
            |--------------------------------------------------------------------------
            | Aktivitas Pendaki
            |--------------------------------------------------------------------------
            */

            'user_routes.view',
            'user_routes.complete',

            /*
            |--------------------------------------------------------------------------
            | Monitoring
            |--------------------------------------------------------------------------
            */

            'live_tracking.view',

            /*
            |--------------------------------------------------------------------------
            | Panduan
            |--------------------------------------------------------------------------
            */

            'trail_guides.view',
            'trail_guides.create',
            'trail_guides.update',
            'trail_guides.delete',
        ]);


        /*
        |--------------------------------------------------------------------------
        | PENDAKI
        |--------------------------------------------------------------------------
        */

        $pendaki =
            Role::firstOrCreate([
                'name' =>
                    'pendaki',

                'guard_name' =>
                    'web',
            ]);


        $pendaki->syncPermissions([

            'pendaki.dashboard.view',

            'pendaki.mountains.view',

            'pendaki.trails.view',

            'pendaki.simaksi.view',
            'pendaki.simaksi.create',

            'pendaki.live_tracking.view',
            'pendaki.live_tracking.start',
            'pendaki.live_tracking.complete',

            'pendaki.sos.send',

            'pendaki.history.view',

            'pendaki.profile.view',
            'pendaki.profile.update',
        ]);


        /*
        |--------------------------------------------------------------------------
        | ADMIN USER YANG SUDAH ADA
        |--------------------------------------------------------------------------
        */

        $adminUser =
            User::query()
                ->where(
                    'email',
                    'admin@gmail.com'
                )
                ->first();


        if (
            $adminUser
        ) {
            $adminUser->syncRoles([
                'admin',
            ]);
        }


        app(
            PermissionRegistrar::class
        )->forgetCachedPermissions();
    }
}