<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ListRolesTableSeeder::class);
        $this->call(TripCategoriesSeeder::class);

        $user = User::create([
            'username' => 'administrator',
            'email' => 'administrator@example.com',
            'password' => bcrypt('123456789'),
            'is_active' => 1,
            'must_change' => 0,
        ]);

        $user->profile()->create([
            'lastname' => 'Administrator',
            'firstname' => 'NMS',
        ]);

        $user->myroles()->create([
            'role_id' => 1,
            'added_by' => $user->id,
            'is_active' => 1,
        ]);
    }
}
