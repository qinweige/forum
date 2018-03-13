<?php

namespace Tests\Feature;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->thread = create('App\Thread');

    }

    /** @test */
    public function a_user_can_browse_threads()
    {
        $response = $this->get('/threads');
        $response->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_thread()
    {
        $response = $this->get($this->thread->path());
        $response->assertSee($this->thread->title);
    }

    /** @test */
    public function a_thread_should_have_a_reply_to_show()
    {
        $reply = create('App\Reply', ['thread_id' => $this->thread->id]);
        $response = $this->get($this->thread->path());
        $response->assertSee($reply->body);
    }
    /** @test */
    public function unauthorized_user_can_not_see_form()
    {
        $this->withExceptionHandling()->get('/threads/create')->assertRedirect('/login');
    }

    /** @test */
    public function thread_path()
    {
        $this->assertEquals("/threads/{$this->thread->channel->slug}/{$this->thread->id}",
            $this->thread->path());
    }

    /** @test */
    public function a_channel_should_have_its_threads()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread');

        $this->get('/threads/'. $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    public function get_my_threads()
    {
        $this->signIn(create('App\User', ['name'=>'weige']));
        $threadsByWeige = create('App\Thread', ['user_id'=>auth()->id()]);
        $threadByOther = create('App\Thread');

        $this->get('/threads?by=weige')
            ->assertSee($threadsByWeige->title)
            ->assertDontSee($threadByOther->title);
    }
}
