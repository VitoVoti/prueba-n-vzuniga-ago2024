<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Jobs\UpdateGithubInfoFromUser;
use App\Services\GitHubService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    // Al crear o modificar usuario, si cambia github_username, se añade a la cola 
    // la tarea de buscar el avatar de GitHub, descargarlo y asociarlo al usuario
    protected static function boot()
    {
        parent::boot();
        self::created(function ($user) {
            if ($user->isDirty('github_username')) {
                UpdateGithubInfoFromUser::dispatch($user);
            }
        });
        self::updated(function ($user) {
            if ($user->isDirty('github_username')) {
                UpdateGithubInfoFromUser::dispatch($user);
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'github_username',
        'position',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    // Custom: roles a través de user_roles
    // Ya que no tendremos interfaz para asignar roles (todo desde seeders/consola) no necesitamos un modelo UserRole
    public function roles(){
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function isAdmin(){
        return $this->roles()->where('name', 'admin')->exists();
    }
    public function isRegularUser(){
        return !$this->isAdmin();
    }

    public function repos(){
        return $this->hasMany(Repo::class);
    }

    public function articles(){
        return $this->hasMany(Article::class);
    }



}
