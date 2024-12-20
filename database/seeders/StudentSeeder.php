<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = Plan::all();

        // Create 20 students with different plans
        foreach(range(1, 20) as $index) {
            $plan = $plans->random();
            $startDate = Carbon::now()->subDays(rand(1, 60));
            
            // Create user first
            $user = User::create([
                'name' => "Student {$index}",
                'email' => "student{$index}@example.com",
                'password' => Hash::make('password'),
                'role' => 'staff'
            ]);
            
            Student::create([
                'user_id' => $user->id,
                'phone' => "1199999" . str_pad($index, 4, '0', STR_PAD_LEFT),
                'birth_date' => Carbon::now()->subYears(rand(18, 50))->subDays(rand(1, 365)),
                'emergency_contact' => "Emergency Contact {$index}",
                'emergency_phone' => "1198888" . str_pad($index, 4, '0', STR_PAD_LEFT),
                'health_conditions' => rand(0, 1) ? "Some health condition {$index}" : null,
                'plan_id' => $plan->id,
                'plan_start_date' => $startDate,
                'plan_end_date' => $startDate->copy()->addMonths($plan->duration_months),
                'monthly_fee' => $plan->price,
                'payment_day' => rand(1, 28),
                'active' => true,
            ]);
        }
    }
}
