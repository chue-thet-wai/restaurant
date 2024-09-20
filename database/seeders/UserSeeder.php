<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the admin role if it doesn't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create the user
        $user = User::firstOrCreate(
            [
                'email' => 'admin@email.com',
            ],
            [
                'name'       => 'Admin User',
                'password'   => Hash::make('test123'),
                'is_admin'   => true,
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]
        );

        // Assign the admin role to the user
        $user->roles()->syncWithoutDetaching([$adminRole->id]);
    }
}