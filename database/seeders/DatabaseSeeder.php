<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $password = env('PASSWORD', 'password');
        User::factory()->create([
            'name' => 'Harley O\'Connor',
            'email' => 'admin@harleyoconnor.com',
            'password' => Hash::make($password),
            'is_admin' => true,
        ]);
    }
}
