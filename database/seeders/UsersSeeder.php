<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create first user - Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@sdjic.org',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create second user - Regular User
        $user = User::create([
            'name' => 'Nehal Patel',
            'email' => 'nehal.sdjic@sdjic.org',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create SDJIC-Vesu team
        $sdjicVesuTeam = Team::forceCreate([
            'user_id' => $admin->id,
            'name' => 'SDJIC-Vesu',
            'personal_team' => false,
        ]);

        // Add both users to SDJIC-Vesu team
        $sdjicVesuTeam->users()->attach([
            $admin->id => ['role' => 'admin'],
            $user->id => ['role' => 'editor'],
        ]);

        // Set current team for users
        $admin->switchTeam($sdjicVesuTeam);

        // Create SDJIC-Palsana team
        $sdjicPalsanaTeam = Team::forceCreate([
            'user_id' => $user->id,
            'name' => 'SDJIC-Palsana',
            'personal_team' => false,
        ]);

        // Add both users to SDJIC-Palsana team
        $sdjicPalsanaTeam->users()->attach([
            $admin->id => ['role' => 'admin'],
            $user->id => ['role' => 'admin'],
        ]);

        $user->switchTeam($sdjicPalsanaTeam);

        $this->command->info('Users and Teams seeded successfully!');
        $this->command->info('Admin: admin@admin.com / password');
        $this->command->info('User: nehal.sdjic@admin.com / password');
        $this->command->info('Teams created:');
        $this->command->info('- SDJIC-Vesu');
        $this->command->info('- SDJIC-Palsana');
    }
}