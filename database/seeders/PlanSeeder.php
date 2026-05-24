<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::updateOrCreate(['name' => 'Starter PHP'], [
            'price' => 15000,
            'duration_months' => 1,
            'max_storage_mb' => 500,
            'max_databases' => 1,
            'description' => 'Perfect for simple PHP student projects.',
            'type' => 'PHP',
        ]);

        Plan::updateOrCreate(['name' => 'Node Basic'], [
            'price' => 25000,
            'duration_months' => 1,
            'max_storage_mb' => 1000,
            'max_databases' => 1,
            'description' => 'Affordable NodeJS hosting for beginners.',
            'type' => 'NodeJS',
        ]);

        Plan::updateOrCreate(['name' => 'Fullstack Pro'], [
            'price' => 50000,
            'duration_months' => 1,
            'max_storage_mb' => 5000,
            'max_databases' => 3,
            'description' => 'Complete support for PHP and Node with multiple DBs.',
            'type' => 'Fullstack',
        ]);
    }
}
