<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Student;

class CreateStudentProfiles extends Command
{
    protected $signature = 'students:create-profiles';
    protected $description = 'Create student profiles for users with student role';

    public function handle()
    {
        $users = User::where('role', 'student')->get();
        $count = 0;

        foreach ($users as $user) {
            if (!$user->student) {
                Student::create([
                    'user_id' => $user->id,
                    'active' => true
                ]);
                $count++;
            }
        }

        $this->info("Created {$count} student profiles");
    }
} 