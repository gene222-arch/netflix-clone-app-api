<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('casts')->insert([
            [
                'pseudonym' => '',
                'birth_name' => 'Mone Kamishiraishi',
                'gender' => 'Female',
                'height_in_cm' => '152',
                'biographical_information' => 'Mone Kamishiraishi was born on January 27, 1998 in Kagoshima, Japan. She is an actress, known for Your Name. (2016), Lady Maiko (2014) and Wolf Children (2012).',
                'birth_details' => '',
                'date_of_birth' => '1998-01-27',
                'place_of_birth' => '',
                'death_details' => '',
                'date_of_death' => '',
                'enabled' => false,
            ]
        ]);
    }
}
