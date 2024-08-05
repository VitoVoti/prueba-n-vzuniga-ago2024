<?php

namespace Database\Seeders;

use App\Models\Role;
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
        $admin_email = config('default_admin_data.email');
        $admin_password = config('default_admin_data.password') ? config('default_admin_data.password') : Str::random(8);
        $admin_name = config('default_admin_data.name');
        $admin_position = config('default_admin_data.position');
        $github_username = config('default_admin_data.github_username');

        $u = User::create([
            'name' => $admin_name,
            'email' => $admin_email,
            'password' => $admin_password,
            'position' => $admin_position,
        ]);

        $u->github_username = $github_username;

        // Asignamos el rol admin al usuario
        $rol_admin = Role::where('name', 'admin')->first();
        $u->roles()->attach($rol_admin);
        $u->save();

        $this->command->getOutput()->writeln("Usuario administrador creado: " . $admin_email . " - Contraseña: " . $admin_password . " - Usuario de GitHub: " . $github_username);
    }
}
