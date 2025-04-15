<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    protected $connection = 'mysql'; // or your target connection

    public function run(): void
    {
        DB::connection($this->connection)->table('users')->truncate();

        $user = new User([
            'name' => env('ADMIN_NAME'),
            'email' => env('ADMIN_EMAIL'),
            'password' => Hash::make(env('ADMIN_PASSWORD')),
        ]);

        $user->setConnection($this->connection)->save();
    }
}
