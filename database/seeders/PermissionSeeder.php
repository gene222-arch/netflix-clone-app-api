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
                'guard_name' => 'api'
            ],
            [
                'name' => 'Manage Casts',
                'guard_name' => 'api'
            ],
            [
                'name' => 'Manage Directors',
                'guard_name' => 'api'
            ],
            [
                'name' => 'Manage Genres',
                'guard_name' => 'api'
            ],
            [
                'name' => 'Manage Movies',
                'guard_name' => 'api'
            ],
            [
                'name' => 'Manage Coming Soon Movies',
                'guard_name' => 'api'
            ],
            [
                'name' => 'View Dashboard',
                'guard_name' => 'api'
            ],
            [
                'name' => 'Manage Access Rights',
                'guard_name' => 'api'
            ]
        ]);
    }
}
