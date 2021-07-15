<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('genres')->insert([
            [
                'name' => 'Drama',
                'enabled' => false
            ],
            [
                'name' => 'Romance',
                'enabled' => false,
            ],
            [
                'name' => 'Animation',
                'enabled' => false
            ]
        ]);
    }
}
