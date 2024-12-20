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
        // Basic Plan
        Plan::create([
            'name' => 'Basic Plan',
            'description' => 'Access to gym facilities during regular hours',
            'price' => 89.90,
            'duration_months' => 1,
            'classes_per_week' => null,
            'active' => true,
        ]);

        // Standard Plan
        Plan::create([
            'name' => 'Standard Plan',
            'description' => 'Access to gym facilities and 2 classes per week',
            'price' => 129.90,
            'duration_months' => 1,
            'classes_per_week' => 2,
            'active' => true,
        ]);

        // Premium Plan
        Plan::create([
            'name' => 'Premium Plan',
            'description' => 'Unlimited access to gym facilities and classes',
            'price' => 199.90,
            'duration_months' => 1,
            'classes_per_week' => null, // unlimited
            'active' => true,
        ]);

        // Annual Plan
        Plan::create([
            'name' => 'Annual Plan',
            'description' => 'Standard plan with 12-month commitment (20% discount)',
            'price' => 99.90,
            'duration_months' => 12,
            'classes_per_week' => 2,
            'active' => true,
        ]);
    }
}
