<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{

    use RefreshDatabase;
    /** @test */
    public function a_user_can_reply_to_thread()
    {
        $this->signin();
        $thread = create('App\Thread');
        $reply = make('App\Reply', array('title'=>null));
        $this->post($thread->path() . '/replies', $reply->toArray());
        $this->get($thread->path())
            ->assertSee($reply->body);
    }

    /** @test */
    public function unauthorize_user_can_not_reply()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $user = factory('App\User')->create();
        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->make();
        $this->post($thread->path() . '/replies', $reply->toArray());
    }
}
