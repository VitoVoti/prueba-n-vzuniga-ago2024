<?php

namespace Tests\Unit\Jobs;

use App\Jobs\UpdateGithubInfoFromUser;
use App\Models\User;
use App\Services\GitHubService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class UpdateGithubInfoFromUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_get_repos_from_django_user_including_daphne(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create([
            'github_username' => 'django',
        ]);
        $githubService = Mockery::mock(GitHubService::class);
        $job = new UpdateGithubInfoFromUser($user, $githubService);
        $job->handle();

        Log::debug("User repos: " . $user->repos()->count());

        // Probamos que se guardaron repos, incluyendo daphne
        $this->assertTrue($user->repos()->count() > 1);
        $this->assertTrue($user->repos()->where('title', 'daphne')->exists());
    }

    public function test_get_avatar_from_django_user(): void {
        $this->withoutExceptionHandling();
        $user = User::factory()->create([
            'github_username' => 'django',
        ]);
        $githubService = Mockery::mock(GitHubService::class);
        $job = new UpdateGithubInfoFromUser($user, $githubService);
        $job->handle();

        // Probamos que se guardó profile_photo_path
        $this->assertTrue($user->profile_photo_path != null);

        // Probamos que se guardó una imagen en storage
        $this->assertTrue(Storage::disk('public')->exists($user->profile_photo_path));
    }

    public function test_old_repos_are_deleted_and_new_ones_added(): void {
        // Simulamos que usuario django tenía guardado antes un repo llamado old con Id 99999, y que teníamos guardado daphne con su Id correcto. El método debe quitar old, dejar daphne y llenar con los demás
        
        $this->withoutExceptionHandling();
        $user = User::factory()->create([
            'github_username' => 'django',
        ]);
        $user->repos->each->delete();
        $user->repos()->create([
            'github_id' => 99999,
            'title' => 'old',
            'github_full_name' => 'django/djangoold',
            'description' => 'repo antiguo, pls delete',
            'url' => 'https://github.com/django/djangoold',
        ]);

        $user->repos()->create([
            'github_id' => 48501841,
            'title' => 'daphne',
            'github_full_name' => 'django/daphne',
            'description' => 'repo correcto, mantener',
            'url' => 'https://github.com/django/daphne',
        ]);

        $this->assertCount(2, $user->repos()->get());

        $githubService = Mockery::mock(GitHubService::class);
        $job = new UpdateGithubInfoFromUser($user, $githubService);
        $job->handle();

        $this->assertTrue( $user->repos()->count() > 2);
        $this->assertTrue($user->repos()->where(['title' => 'daphne', 'github_id' => 48501841])->exists());
        $this->assertTrue($user->repos()->where(['title' => 'old'])->doesntExist());
        $this->assertTrue($user->repos()->where(['title' => 'django', 'github_id' => 4164482])->exists());

        
    }

}