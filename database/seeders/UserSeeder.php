<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'Gene Phillip',
            'last_name' => 'Artista',
            'email' => 'genephillip222@gmail.com',
            'password' => Hash::make('genephillip222@gmail.com')
        ]);
    }
}
