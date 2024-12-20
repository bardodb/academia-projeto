<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@academia.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create staff users
        User::create([
            'name' => 'Staff User',
            'email' => 'staff@academia.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        // Create additional staff users
        User::create([
            'name' => 'John Doe',
            'email' => 'john@academia.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@academia.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);
    }
}
