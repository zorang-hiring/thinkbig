<?php

namespace Database\Seeders;

use App\Enum\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Name',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('pass123'),
            'api_token' => 'an_admin_token',
            'roles' => [UserRole::ROLE_ADMIN]
        ]);
    }
}
