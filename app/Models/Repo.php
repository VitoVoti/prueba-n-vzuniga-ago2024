<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repo extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'url', 'github_id', 'github_full_name'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
