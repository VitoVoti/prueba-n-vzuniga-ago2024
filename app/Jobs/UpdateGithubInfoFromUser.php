<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\GitHubService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UpdateGithubInfoFromUser implements ShouldQueue
{
    use Queueable;

    protected $user;

    // Se espera un App\User
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    // Se saca la URL del avatar de GitHub del usuario, se descarga y se guarda en storage
    public function handle(): void
    {
        if($this->user->github_username == null) return;

        $github_username = $this->user->github_username;

        // Usamos GitHubService
        $data = GitHubService::getInfoFromUsername($github_username);
        Log::debug("GitHubService " . json_encode($data));

        if($data == "") return;

        $avatar_url = $data['avatar_url'];
        Log::debug("avatar_url is " . $avatar_url);
        $avatar_content = Http::get($avatar_url)->body();
        $avatar_path = 'github_avatars/' . $github_username . '.jpg';
        Storage::disk('public')->put($avatar_path, $avatar_content);
        $this->user->profile_photo_path = $avatar_path;
        $this->user->save();
    }
}
