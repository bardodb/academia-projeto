<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\CheckIn;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CheckInSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        
        // Create check-ins for the past 30 days
        foreach($students as $student) {
            // Generate 15-20 check-ins per student over the last 30 days
            $numberOfCheckIns = rand(15, 20);
            
            for($i = 0; $i < $numberOfCheckIns; $i++) {
                $checkInTime = Carbon::now()->subDays(rand(1, 30))->setHour(rand(6, 20))->setMinute(rand(0, 59));
                
                CheckIn::create([
                    'student_id' => $student->id,
                    'check_in_time' => $checkInTime,
                    'check_out_time' => $checkInTime->copy()->addHours(rand(1, 3)),
                    'qr_code' => Str::uuid(),
                ]);
            }
        }
    }
}
