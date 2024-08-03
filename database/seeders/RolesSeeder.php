<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $admin_role = Role::create(['name' => 'admin']);
        $user_role = Role::create(['name' => 'user']);
    }
}