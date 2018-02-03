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
        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->create();
        $this->post('/threads/' . $thread->id . '/replies', $reply->toArray())
            ->assertRedirect('/login');
    }
    /** @test  */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->be($user = factory('App\User')->create());
        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->create();
        $this->post('/threads/' . $thread->id . '/replies', $reply->toArray());
        // $this->get('/threads/' . $thread->id);
        $this->get($thread->path())
            ->assertSee($reply->body);
    }
}
