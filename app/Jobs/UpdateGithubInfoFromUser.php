<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\GitHubService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
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

        if($github_username == null) return;

        // Usamos GitHubService
        $data = GitHubService::getInfoFromUsername($github_username);
        Log::debug("UpdateGithubInfoFromUser " . $github_username . " | GitHubService retorna " . json_encode(count($data) ? count($data) : "0") . " keys");

        if($data == "") return;
        
        // Primero llenamos avatar, se descarga y guarda en storage
        try {
            $avatar_url = $data['avatar_url'];
            Log::debug("avatar_url is " . $avatar_url);
            $avatar_content = Http::get($avatar_url)->body();
            $avatar_path = 'github_avatars/' . $github_username . '.jpg';
            Storage::disk('public')->put($avatar_path, $avatar_content);
            $this->user->profile_photo_path = $avatar_path;
            $this->user->save();
        } catch (\Exception $e) {
            Log::error("Error al descargar avatar de GitHub para usuario " . $github_username . ": " . $e->getMessage());
        }

        // Ahora sincronizo los repos, con cuidado de no escribir dos veces los existentes, y quitando los que ya no existen
        try {
            $repos_data = GitHubService::getReposFromUsername($github_username);
            Log::debug("UpdateGithubInfoFromUser " . $github_username . " | getReposFromUsername is " . json_encode(count($repos_data) ? count($repos_data) : "0") . " keys");
            if($repos_data != ""){

                $existing_repos = $this->user->repos()->pluck('github_id')->toArray();
                Log::debug("UpdateGithubInfoFromUser " . $github_username . " | existing_repos length is " . json_encode(count($existing_repos)));

                // Todo dentro de una transacción
                DB::transaction(function () use ($existing_repos, $repos_data, $github_username) {

                    $repos_data_only_ids = collect($repos_data)->pluck('id')->toArray();

                    // Saco repos que no estén añadidos anteriormente, y los agrego
                    $repos_data_filtered = array_filter($repos_data, function ($repo) use ($existing_repos) {
                        return !in_array($repo['id'], $existing_repos);
                    });
                    
                    $data_a_guardar = collect($repos_data_filtered)->map(function ($repo) {
                        return [
                            'title' => $repo['name'],
                            'description' => $repo['description'],
                            'url' => $repo['html_url'],
                            'github_id' => $repo['id'],
                            'github_full_name' => $repo['full_name'],
                        ];
                    });

                    Log::debug("UpdateGithubInfoFromUser " . $github_username . " | data_a_guardar length is " . json_encode(count($data_a_guardar)));
                    $this->user->repos()->createMany($data_a_guardar);

                    // Ahora quito repos que ya no estén
                    $repos_a_quitar = $this->user->repos()->whereNotIn('github_id', $repos_data_only_ids)->get();
                    Log::debug("UpdateGithubInfoFromUser " . $github_username . " | repos_a_quitar length is " . json_encode($repos_a_quitar->count()) . " repos_data_only_ids es " . json_encode($repos_data_only_ids) . " ids de repos recien guardados es " . json_encode($data_a_guardar->pluck('github_id')->toArray()) . " ids de repos a quitar son " . json_encode($repos_a_quitar->pluck('github_id')->toArray()));
                    $repos_a_quitar->each->delete();

                    
                });


            } else {
                Log::debug("No hay repos para sincronizar para usuario " . $github_username . ", repos data es " . json_encode($repos_data));
            }
        } catch (\Exception $e) {
            Log::error("Error al sincronizar repos de GitHub para usuario " . $github_username . ": " . $e->getMessage());
        }
    }
}
