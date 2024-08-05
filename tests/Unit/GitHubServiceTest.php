<?php

namespace Tests\Unit\Jobs;

use App\Jobs\UpdateGithubInfoFromUser;
use App\Models\User;
use App\Services\GitHubService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class GitHubServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_get_info_from_username_receives_correct_id()
    {
        $user = User::factory([
            'github_username' => 'microsoft',
        ])->create();

        $info_from_username = GitHubService::getInfoFromUsername($user->github_username);
        // Probamos que se recibe el id correcto
        $this->assertEquals(
            6154722,
            $info_from_username["id"]
        );
    }

    public function test_get_info_from_username_receives_avatar_url()
    {
        $user = User::factory([
            'github_username' => 'microsoft',
        ])->create();

        $info_from_username = GitHubService::getInfoFromUsername($user->github_username);
        // Probamos que se recibe el URL con el avatar
        $this->assertStringStartsWith(
            'https://avatars.githubusercontent.com/u/6154722', 
            $info_from_username['avatar_url']
        ); 
    }
}