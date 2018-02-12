<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;
    /** @test  */
    public function unauthenticated_users_may_not_add_replies()
    {
        $this->withExceptionHandling();
        $thread = create('App\Thread');
        $reply = make('App\Reply');
        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertRedirect('/login');
    }
    /** @test  */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->be($user = factory('App\User')->create());
        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->make();
        $this->post($thread->path() . '/replies', $reply->toArray());
        // $this->get('/threads/' . $thread->id);
        $this->get($thread->path())
            ->assertSee($reply->body);
    }
    /** @test  */
    public function a_reply_requires_a_body()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $thread = create('App\Thread');
        $reply = make('App\Reply', ['body' => null]);
        $this->post($thread->path() . '/replies', $reply->toArray());
        $this->get($thread->path() . '/replies')
            ->assertSessionHasErrors('body');
    }
}
