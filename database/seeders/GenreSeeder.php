<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [];
        for ($i=0; $i < 1000; $i++) { 
            $arr = [
                ...$arr, 
                [
                    'name' => Str::random(10)
                ]
            ];
        }

        DB::table('genres')->insert($arr);

        // DB::table('genres')->insert([
        //     [
        //         'name' => 'Drama',
        //         'enabled' => false
        //     ],
        //     [
        //         'name' => 'Romance',
        //         'enabled' => false,
        //     ],
        //     [
        //         'name' => 'Animation',
        //         'enabled' => false
        //     ],
        //     [
        //         'name' => 'Fantasy',
        //         'enabled' => false
        //     ],
        //     [
        //         'name' => 'Anime',
        //         'enabled' => false
        //     ]
        // ]);
    }
}
