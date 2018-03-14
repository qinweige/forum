<?php

namespace Tests\Feature;

use Mockery\Exception;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function an_authenticated_user_can_favorite()
    {
        $this->signIn();
        $reply = create('App\Reply');
        $response = $this->post('/replies/' . $reply->id . '/favorites');
            $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function an_authenticated_user_cannot_twice_favorite()
    {
        $this->signIn();
        $reply = create('App\Reply');
        try {
            $response = $this->post('/replies/' . $reply->id . '/favorites');
            $response = $this->post('/replies/' . $reply->id . '/favorites');
        } catch (Exception $e) {
            $this->fail('can not favorite twice');
        }
        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function an_unauthenticated_user_cannot_favorite()
    {
        $this->withExceptionHandling();
        $this->post('/replies/1/favorites')
            ->assertRedirect('login');
    }
}
