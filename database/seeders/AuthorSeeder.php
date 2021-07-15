<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('authors')->insert([
            [
                'pseudonym' => '',
                'birth_name' => 'Makoto Shinkai',
                'gender' => 'Male',
                'height_in_cm' => 180.34,
                'biographical_information' => "Makoto Niitsu, also known as Makoto Shinkai, is a Japanese animator, filmmaker and manga artist best known for directing Your Name, the third highest-grossing anime film of all time and 2019's Weathering",
                'birth_details' => '',
                'date_of_birth' => '1973-02-09',
                'place_of_birth' => '',
                'death_details' => '',
                'date_of_death' => '',
                'enabled' => false,
            ]
        ]);
    }
}
