<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SimaksiPermissionSeeder extends Seeder
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

            'simaksi.view',

            'simaksi.update',

            'simaksi.approve',

            'simaksi.reject',

            'simaksi.complete',

            'simaksi.delete',

        ];


        foreach (
            $permissions
            as
            $permission
        ) {
            Permission::firstOrCreate([
                'name' =>
                    $permission,

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
            Role::query()
                ->where(
                    'name',
                    'admin'
                )
                ->where(
                    'guard_name',
                    'web'
                )
                ->first();


        if ($admin) {
            $admin->givePermissionTo(
                $permissions
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PENGELOLA PENDAKIAN
        |--------------------------------------------------------------------------
        */

        $manager =
            Role::query()
                ->where(
                    'name',
                    'pengelola_lokasi'
                )
                ->where(
                    'guard_name',
                    'web'
                )
                ->first();


        if ($manager) {
            $manager->givePermissionTo([

                'simaksi.view',

                'simaksi.update',

                'simaksi.approve',

                'simaksi.reject',

                'simaksi.complete',

            ]);
        }


        app(
            PermissionRegistrar::class
        )->forgetCachedPermissions();
    }
}