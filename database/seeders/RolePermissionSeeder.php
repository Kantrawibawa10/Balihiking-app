<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
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
            | DASHBOARD
            |--------------------------------------------------------------------------
            */

            'dashboard.view',

            /*
            |--------------------------------------------------------------------------
            | MOUNTAINS
            |--------------------------------------------------------------------------
            */

            'mountains.view',
            'mountains.create',
            'mountains.update',
            'mountains.delete',

            /*
            |--------------------------------------------------------------------------
            | TRAILS
            |--------------------------------------------------------------------------
            */

            'trails.view',
            'trails.create',
            'trails.update',
            'trails.delete',

            /*
            |--------------------------------------------------------------------------
            | CHECKPOINTS
            |--------------------------------------------------------------------------
            */

            'checkpoints.view',
            'checkpoints.create',
            'checkpoints.update',
            'checkpoints.delete',

            /*
            |--------------------------------------------------------------------------
            | TRAIL REPORTS
            |--------------------------------------------------------------------------
            */

            'trail_reports.view',
            'trail_reports.update',

            /*
            |--------------------------------------------------------------------------
            | USER ROUTES
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
            | GUIDES
            |--------------------------------------------------------------------------
            */

            'trail_guides.view',
            'trail_guides.create',
            'trail_guides.update',
            'trail_guides.delete',

            /*
            |--------------------------------------------------------------------------
            | USERS
            |--------------------------------------------------------------------------
            */

            'users.view',
            'users.create',
            'users.update',
            'users.delete',

            /*
            |--------------------------------------------------------------------------
            | ROLE / PERMISSION
            |--------------------------------------------------------------------------
            */

            'roles.manage',

            /*
            |--------------------------------------------------------------------------
            | ASSIGNMENT
            |--------------------------------------------------------------------------
            */

            'location_assignments.manage',
        ];


        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $admin->syncPermissions(
            Permission::all()
        );


        /*
        |--------------------------------------------------------------------------
        | LOCATION MANAGER
        |--------------------------------------------------------------------------
        */

        $locationManager =
            Role::firstOrCreate([
                'name' => 'pengelola_lokasi',
                'guard_name' => 'web',
            ]);


        $locationManager
            ->syncPermissions([

                'dashboard.view',

                'mountains.view',
                'mountains.update',

                'trails.view',
                'trails.create',
                'trails.update',
                'trails.delete',

                'checkpoints.view',
                'checkpoints.create',
                'checkpoints.update',
                'checkpoints.delete',

                'trail_reports.view',
                'trail_reports.update',

                'user_routes.view',
                'user_routes.complete',

                'live_tracking.view',

                'trail_guides.view',
                'trail_guides.create',
                'trail_guides.update',
                'trail_guides.delete',
            ]);


        app(
            PermissionRegistrar::class
        )->forgetCachedPermissions();
    }
}