<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Article;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class UserCRUDTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_user_can_create_article(): void
    {
        $response = $this->get('/');

        $user = User::factory()->create();

        $tags = Tag::factory(5)->create();

        $response = $this
            ->actingAs($user)
            ->from('/articles')
            ->post('/articles', [
                'title' => 'Some title',
                'body' => 'Some body',
                'tags' => $tags->pluck('id')->toArray(),
            ]);
        
        $possible_article = Article::where('title', 'Some title')->first();
        $this->assertTrue($possible_article && $possible_article->exists());
        $this->assertTrue($possible_article->tags->count() == 5);

        // Todo: revisar nombres
        
        $response->assertStatus(302);
        

    }



    public function test_user_cannot_edit_someone_elses_article(): void
    {
        $response = $this->get('/');

        $user = User::factory()->create();
        $user_2 = User::factory()->create();

        $possible_article = Article::factory()->create([
            'user_id' => $user_2->id
        ]);

        $tags = Tag::factory(2)->create();

        $response = $this
            ->actingAs($user)
            ->from('/articles')
            ->put('/articles' . '/' . $possible_article->id , [
                'title' => 'Some title CHANGED',
                'body' => 'Some body CHANGED',
                'tags' => $tags->pluck('id')->toArray(),
            ],);
        $possible_article = Article::where('title', 'Some title CHANGED')->first();
        $this->assertFalse($possible_article && $possible_article->exists());
        
        $response->assertStatus(403);
        

    }

    public function test_user_cannot_crud_tags(): void
    {
        $response = $this->get('/');

        $user = User::factory()->create();

        $tag = Tag::factory()->create();

        // Create
        $response = $this
            ->actingAs($user)
            ->from('/tags')
            ->post('/tags', [
                'name' => 'Some Tag',
            ]);

        $possible_tag = Tag::where('name', 'Some Tag')->first();
        $this->assertFalse($possible_tag && $possible_tag->exists);
        
        $response->assertStatus(403);

        // Update
        $response = $this
            ->actingAs($user)
            ->from('/tags')
            ->put('/tags' . '/' . $tag->id , [
                'name' => 'Some Tag CHANGED',
            ]);

        $possible_tag = Tag::where('name', 'Some Tag CHANGED')->first();
        $this->assertFalse($possible_tag && $possible_tag->exists);
        
        $response->assertStatus(403);

        // Destroy
        
        $tag_name = $tag->name;
        $response = $this
            ->actingAs($user)
            ->from('/tags')
            ->delete('/tags' . '/' . $tag->id);

        $possible_tag = Tag::where('name', $tag_name)->first();
        $this->assertTrue($possible_tag && $possible_tag->exists);
        
        $response->assertStatus(403);
        

    }
}
