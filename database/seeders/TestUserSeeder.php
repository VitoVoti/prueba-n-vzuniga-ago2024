<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;


class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $name = "Solidus Snake";
        $email = "test@test.com";
        $password = "asdfghjk";
        $position = "Developer";
        $github_username = "solidussnake";

        $u = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'position' => $position,
            'github_username' => $github_username,
        ]);

        // Asignamos el rol admin al usuario
        //$rol_user = Role::where('name', 'user')->first();
        //$u->roles()->attach($rol_user);
        $u->save();

        $this->command->getOutput()->writeln("Usuario creado: " . $email . " - Contraseña: " . $password . " - Usuario de GitHub: " . $github_username);
    }
}
