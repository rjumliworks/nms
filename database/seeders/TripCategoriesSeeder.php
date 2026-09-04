<?php

namespace Database\Seeders;

use App\Models\ListDropdown;
use Illuminate\Database\Seeder;

class TripCategoriesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Hull Boat', 'classification' => 'Expense', 'type' => 'Category'],
            ['name' => 'Carrier', 'classification' => 'Expense', 'type' => 'Category'],
            ['name' => 'Hull Boat', 'classification' => 'Loan', 'type' => 'Category'],
            ['name' => 'Service', 'classification' => 'Loan', 'type' => 'Category'],
        ];

        foreach ($categories as $category) {
            ListDropdown::updateOrCreate(
                [
                    'name' => $category['name'],
                    'classification' => $category['classification'],
                    'type' => $category['type'],
                ],
                [
                    'color' => 'n/a',
                    'others' => 'n/a',
                    'is_active' => 1,
                ]
            );
        }
    }
}
