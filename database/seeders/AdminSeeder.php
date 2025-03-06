<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
         // Delete all users before seeding
         DB::table('users')->truncate();

         // Create admin user
         User::create([
             'name' => 'Admin',
             'email' => 'admin@example.com',
             'password' => Hash::make('password123'), // Change this to a secure password
         ]);
    }
}
