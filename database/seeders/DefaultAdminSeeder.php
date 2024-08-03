<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;


class DefaultAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Tomamos los datos de admin desde el .env
        $adminEmail = config('default_admin_data.email');
        $adminPassword = config('default_admin_data.password') ? config('default_admin_data.password') : Str::random(8);
        $adminName = config('default_admin_data.name');
        $adminPosition = config('default_admin_data.position');

        User::create([
            'name' => $adminName,
            'email' => $adminEmail,
            'password' => $adminPassword,
            'position' => $adminPosition,
        ]);

        $this->command->getOutput()->writeln("Usuario administrador creado: " . $adminEmail . " - Contraseña: " . $adminPassword);
    }
}
