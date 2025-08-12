<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name'              => 'Test User',
                'password'          => Hash::make('password123'),
                'email_verified_at' => now(),
                'remember_token'    => Str::random(10),
            ]
        );
    }
}