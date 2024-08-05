<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Por defecto solo se ejecutan estos 2 seeders, los otros son para testing y se deben ejecutar manualmente
        $this->call(RolesSeeder::class);
        $this->call(DefaultAdminSeeder::class);

    }
}
