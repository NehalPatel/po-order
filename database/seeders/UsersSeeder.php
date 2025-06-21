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
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create personal team for admin
        $admin->ownedTeams()->save(Team::forceCreate([
            'user_id' => $admin->id,
            'name' => explode(' ', $admin->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));

        // Create second user - Regular User
        $user = User::create([
            'name' => 'Nehal Patel',
            'email' => 'nehal.sdjic@admin.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create personal team for regular user
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));

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

        $this->command->info('Users and Teams seeded successfully!');
        $this->command->info('Admin: admin@admin.com / password');
        $this->command->info('User: nehal.sdjic@admin.com / password');
        $this->command->info('Teams created:');
        $this->command->info('- Admin\'s Team (Personal)');
        $this->command->info('- Nehal\'s Team (Personal)');
        $this->command->info('- SDJIC-Vesu (Shared)');
        $this->command->info('- SDJIC-Palsana (Shared)');
    }
}