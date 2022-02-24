<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Admin::create(['name' => 'admin', 'phone_number' => '123123' , 'email' => 'admin@admin.com','password'=>bcrypt('admin')]);
    }
}
