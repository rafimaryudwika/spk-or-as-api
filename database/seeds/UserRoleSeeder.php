<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['id' => 1, 'role' => 'admin'],
            ['id' => 2, 'role' => 'panitia'],
            ['id' => 3, 'role' => 'penilai']
        ];

        foreach ($roles as $role) {
            DB::table('user_role')->insert($role);
        }
    }
}
