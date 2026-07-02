<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('Admin@123'),
                'role' => 'admin',
                'reference_no' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'Client User',
                'password' => Hash::make('User@123'),
                'role' => 'user',
                'reference_no' => 'user',
            ]
        );
    }
}
