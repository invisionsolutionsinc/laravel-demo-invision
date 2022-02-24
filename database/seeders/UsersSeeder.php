<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create(['name' => 'admin', 'phone_number' => '123123' , 'email' => 'user@user.com','password'=>bcrypt('user')]);
    }
}
