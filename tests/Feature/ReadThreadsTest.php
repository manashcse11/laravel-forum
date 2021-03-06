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
    /** @test */
    public function a_user_can_a_user_can_filter_thread_according_to_a_channel(){
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread');
        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }
    /** @test */
    public function a_user_can_filter_threads_by_any_username(){
        $user = $this->signIn(create('App\User', ['name' => 'JohnDoe']));
        $threadByJohn = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByJohn = create('App\Thread');
        $this->get('threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }
    /** @test */
    public function a_user_can_filter_threads_by_popularity(){
        $threadWithTwoReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithTwoReplies->id], 2);
        
        $threadWithThreeReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithThreeReplies->id], 3);

        $threadWithNoReplies = $this->thread;

        $response = $this->getJson('threads?popular=1')->json();
        $this->assertEquals([3, 2, 0], array_column($response, 'replies_count'));
        
    }

}
