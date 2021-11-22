<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            [
                'name' => 'Manage Authors',
                'guard_name' => 'api',
                'description' => 'Has access to Create, Read, Update and Delete one or multiple authors.'
            ],
            [
                'name' => 'Manage Casts',
                'guard_name' => 'api',
                'description' => 'Has access to Create, Read, Update and Delete one or multiple casts.'
            ],
            [
                'name' => 'Manage Directors',
                'guard_name' => 'api',
                'description' => 'Has access to Create, Read, Update and Delete one or multiple directors.'
            ],
            [
                'name' => 'Manage Genres',
                'guard_name' => 'api',
                'description' => 'Has access to Create, Read, Update and Delete one or multiple genres.'
            ],
            [
                'name' => 'Manage Movies',
                'guard_name' => 'api',
                'description' => 'Has access to Create, Read, Update and Delete one or multiple movies.'
            ],
            [
                'name' => 'Manage Coming Soon Movies',
                'guard_name' => 'api',
                'description' => 'Has access to Create, Release, Read, Update, Delete, and Manage Trailers one or multiple coming soon movies.'
            ],
            [
                'name' => 'View Dashboard',
                'guard_name' => 'api',
                'description' => 'Has access to the Dashboard that displays general data analytics.'
            ],
            [
                'name' => 'Manage Access Rights',
                'guard_name' => 'api',
                'description' => 'Has access to Create, Read, Update and Delete one or multiple user role and permissions. Has access to assign a role to one or multiple users.'
            ], //
            [
                'name' => 'Manage Activity Logs',
                'guard_name' => 'api',
                'description' => 'Can view and delete one or multiple activity logs'
            ],
            [
                'name' => 'Manage Employees',
                'guard_name' => 'api',
                'description' => 'Has access to Create, Read, Update and Delete one or multiple employees and assign role to them.'
            ],
            [
                'name' => 'Manage Subscriptions',
                'guard_name' => 'api',
                'description' => 'Has access to read user subscriptions'
            ]
        ]);
    }
}
