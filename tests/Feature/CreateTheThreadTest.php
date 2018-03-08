<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTheThreadTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function authorized_user_can_create_thread()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $response = $this->post('/threads', $thread->toArray());
        $this->get($response->headers->get('location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
    /** @test */
    public function unauthorized_user_can_not_create_thread()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $thread = factory('App\Thread')->make();
        $this->post('/threads', $thread->toArray());
    }

    /** @test */
    public function a_thread_should_have_title()
    {
        $this->publicThread(['title' => null])
            ->assertSessionHasErrors('title');
    }
    /** @test */
    public function a_thread_should_have_body()
    {
        $this->publicThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_should_have_channel()
    {
        create('App\Channel');
        $this->publicThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');
        $this->publicThread(['channel_id' => 99])
            ->assertSessionHasErrors('channel_id');
    }
    public function publicThread($overWrite = [])
    {
        $this->withExceptionHandling()->signIn();
        $thread = make('App\Thread', $overWrite);
        return $this->post('/threads', $thread->toArray());
    }

}
