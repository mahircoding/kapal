<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Settings;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure roles exist
        if (Role::count() == 0) {
            $this->call(RoleSeeder::class);
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@smicrewingmanagement.co.id'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'whatsapp_number' => '00000000000',
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('admin');

        $hrd = User::firstOrCreate(
            ['email' => 'hrd@smicrewingmanagement.co.id'],
            [
                'name' => 'HRD',
                'password' => Hash::make('password'),
                'whatsapp_number' => '00000000000',
                'email_verified_at' => now(),
            ]
        );

        $hrd->assignRole('hrd');

        // Initial WhatsApp API Key setting (placeholder)
        Settings::firstOrCreate(
            ['key' => 'whatsapp_api_key'],
            ['value' => 'YOUR_API_KEY_HERE']
        );
    }
}
