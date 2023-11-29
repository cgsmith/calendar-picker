<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate([
            'name' => fake()->name(),
            'email' => 'info@mount7.com',
            'email_verified_at' => '2000-10-01',
            'password' => Hash::make('1234'),
            'remember_token' => 1,
        ]);
    }
}
