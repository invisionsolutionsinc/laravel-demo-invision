<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\Role::truncate();
            $addroles = [
                ['name' => 'Super Admin', 'guard_name' => 'users', 'created_at' => Carbon::now()],
                ['name' => 'Manager', 'guard_name' => 'users', 'created_at' => Carbon::now()],
            ];
        \App\Models\Role::insert($addroles);
    }
}
