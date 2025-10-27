<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Nette\Utils\Random;

class TestLoginUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin Noorfikri',
            'email' => 'noorfikri@admin.com',
            'password' => bcrypt('35211235'),
            'remember_token' => Random::generate(10),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
