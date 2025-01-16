<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Check if admin user exists
        $admin = User::where('email', 'admin@admin.com')->first();

        if (!$admin) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]);
        } else {
            // Update existing admin user's role if needed
            $admin->update(['role' => 'admin']);
        }
    }
}
