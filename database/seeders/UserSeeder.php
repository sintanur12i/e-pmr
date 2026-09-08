<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username'  => 'admin',
            'password'  => bcrypt('password'),
            'full_name' => 'Admin PMR',
            'email'     => 'admin@epmr.test',
            'role'      => 'admin',
            'is_active' => true,
        ]);
    }
}