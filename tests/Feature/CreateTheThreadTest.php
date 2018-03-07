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
        $this->actingAs(factory('App\User')->create());
        $thread = factory('App\Thread')->make();
        $this->post('/threads', $thread->toArray());
        $this->get('/threads/' . $thread->id)
            ->assertSee($thread->title);
    }
    /** @test */
    public function unauthorized_user_can_not_create_thread()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $thread = factory('App\Thread')->make();
        $this->post('/threads', $thread->toArray());
    }
}
