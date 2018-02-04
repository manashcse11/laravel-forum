<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;

    public function test_guest_may_not_create_thread()
    {
        $thread = factory('App\Thread')->make();
        $this->post('/threads', $thread->toArray())
            ->assertRedirect('/login');
    }
    public function test_an_authenticated_user_can_create_thread()
    {
        $this->be(factory('App\User')->create());
        $thread = factory('App\Thread')->make();
        $this->post('/threads', $thread->toArray());
        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
