<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Role::count()) {
            // Channel::truncate();
            Role::truncate();
        }

        // Create predefined roles
        $roles = [
            ['name' => 'admin'],
            ['name' => 'teacher'],
            ['name' => 'student'],
            ['name' => 'parent'],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate($roleData);
        }

        $this->command->info('Roles created successfully');
    }
}
