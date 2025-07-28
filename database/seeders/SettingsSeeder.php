<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\Team;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all teams
        $teams = Team::all();

        foreach ($teams as $team) {
            // Create settings for each team
            Setting::create([
                'team_id'        => $team->id,
                'company_name'   => $this->getCompanyName($team),
                'address'        => $this->getFullAddress($team),
                'phone'          => $this->getPhone($team),
                'logo'           => null, // No default logo for seeded data
                'website'        => $this->getWebsite($team),
                'email'          => $this->getEmail($team),
            ]);
        }

        $this->command->info('Settings seeded successfully for ' . $teams->count() . ' teams!');
    }

    /**
     * Get company name based on team
     */
    private function getCompanyName($team): string
    {
        if ($team->name === 'Admin User\'s Team') {
            return 'Admin Corporation';
        } elseif ($team->name === 'Nehal Patel\'s Team') {
            return 'Nehal Enterprises';
        }
        return $team->name . ' Company';
    }

    /**
     * Get full address based on team
     */
    private function getFullAddress($team): string
    {
        if ($team->name === 'Admin User\'s Team') {
            return '123 Admin Street, Admin City, AC 12345';
        } elseif ($team->name === 'Nehal Patel\'s Team') {
            return '456 Business Avenue, Business City, BC 67890';
        }
        return '789 Main Street, Default City, DC 11111';
    }

    /**
     * Get phone based on team
     */
    private function getPhone($team): string
    {
        if ($team->name === 'Admin User\'s Team') {
            return '+1-555-ADMIN-01';
        } elseif ($team->name === 'Nehal Patel\'s Team') {
            return '+1-555-NEHAL-01';
        }
        return '+1-555-000-0000';
    }

    /**
     * Get website based on team
     */
    private function getWebsite($team): string
    {
        if ($team->name === 'Admin User\'s Team') {
            return 'https://admin-corp.com';
        } elseif ($team->name === 'Nehal Patel\'s Team') {
            return 'https://nehal-enterprises.com';
        }
        return 'https://example.com';
    }

    /**
     * Get email based on team
     */
    private function getEmail($team): string
    {
        if ($team->name === 'Admin User\'s Team') {
            return 'contact@admin-corp.com';
        } elseif ($team->name === 'Nehal Patel\'s Team') {
            return 'contact@nehal-enterprises.com';
        }
        return 'contact@example.com';
    }
}