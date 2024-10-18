<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::count()) {
            // Channel::truncate();
            User::truncate();
        }

        $this->createAdminUser();

        $this->createUser(1, 'teacher');
        $this->createUser(2, 'student');
        $this->createUser(3, 'parent');
    }

    private function createAdminUser()
    {
        $user = User::create([
            'name' => 'مدیر اصلی',
            'password' => Hash::make('111111'),
            'email' => 'admin@worksheet.com',
            'mobile' => '+989000000000',
        ]);

        $adminRole = Role::where('name', 'admin')->first();
        $user->roles()->attach($adminRole);
        // $user->save();

    }

    private function createUser($num = 1, $role)
    {
        $user = User::create([
            'name' => 'کاربر ' . $num,
            'password' => Hash::make('111111'),
            'email' => 'user' . $num . '@worksheet.com',
            'mobile' => '+989' . str_repeat($num, 9),
        ]);

        $userRole = Role::where('name', $role)->first();
        $user->roles()->attach($userRole);
        // $user->save();
    }
}
