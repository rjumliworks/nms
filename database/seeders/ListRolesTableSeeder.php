<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ListRolesTableSeeder extends Seeder
{
    public function run()
    {
        \DB::table('list_roles')->delete();

        \DB::table('list_roles')->insert([
            [
                'id' => 1,
                'name' => 'Administrator',
                'type' => 'Staff',
                'is_active' => 1,
                'definition' => 'Has full system access, including managing users, roles, and all system configurations',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
