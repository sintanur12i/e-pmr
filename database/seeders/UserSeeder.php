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

        User::create([
            'username'  => 'budi123',
            'password'  => bcrypt('password'),
            'full_name' => 'Budi Santoso',
            'email'     => 'budi@epmr.test',
            'role'      => 'member',
            'is_active' => true,
        ]);

        User::create([
            'username'  => 'sinta',
            'password'  => bcrypt('password'),
            'full_name' => 'Sinta Nur Cahyati',
            'email'     => 'sinta@epmr.test',
            'role'      => 'candidate_member', 
            'is_active' => true,
        ]);
    }
}