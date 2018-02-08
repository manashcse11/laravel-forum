<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;
    public function setUp(){
        parent::setUp();
        $this->thread = factory('App\Thread')->create();
    }
    /** @test */
    public function a_user_can_view_all_threads()
    {
        $response = $this->get('/threads');

        // $response->assertStatus(200);
        $response->assertSee($this->thread->title);
    }
    /** @test */
    public function a_user_can_read_a_thread(){
        $response = $this->get($this->thread->path());
        $response->assertSee($this->thread->title);
    }
    /** @test */
    public function a_user_can_read_a_replies_that_are_associated_with_a_thread(){
        // Given that we have a thread
        // And that thread includes replies
        $reply = factory('App\Reply')->create(['thread_id' => $this->thread->id]);
        // When we visit a thread page, Then we should see the replies
        $this->get($this->thread->path())
        ->assertSee($reply->body);
    }

}
