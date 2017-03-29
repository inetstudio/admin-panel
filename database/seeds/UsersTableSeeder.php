<?php

namespace InetStudio\AdminPanel\Database\Seeds;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'bukin@inetstudio.ru',
            'password' => bcrypt('password'),
        ]);
    }
}
