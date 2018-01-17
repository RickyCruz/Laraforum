<?php

namespace Tests\Feature;

use App\Reputation;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReputationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_earns_points_when_they_create_a_thread()
    {
        $thread = create('App\Thread');

        $this->assertEquals(
            Reputation::THREAD_WAS_PUBLISHED, $thread->creator->reputation
        );
    }

    /** @test */
    function a_user_lose_points_when_they_delete_a_thread()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->assertEquals(
            Reputation::THREAD_WAS_PUBLISHED, $thread->creator->reputation
        );

        $this->delete($thread->path());

        $this->assertEquals(0, $thread->creator->fresh()->reputation);
    }

    /** @test */
    public function a_user_earns_points_when_they_reply_to_a_thread()
    {
        $thread = create('App\Thread');

        $reply = $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Here is a reply.'
        ]);

        $this->assertEquals(Reputation::REPLY_POSTED, $reply->owner->reputation);
    }

    /** @test */
    public function a_user_loses_points_when_their_reply_to_a_thread_is_deleted()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->assertEquals(Reputation::REPLY_POSTED, $reply->owner->reputation);

        $this->delete(route('replies.destroy',$reply->id));

        $this->assertEquals(0, $reply->owner->fresh()->reputation);
    }

    /** @test */
    public function a_user_earns_points_when_their_reply_is_marked_as_best()
    {
        $thread = create('App\Thread');

        $thread->markBestReply($reply = $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Here is a reply.'
        ]));

        $reputation = Reputation::REPLY_POSTED + Reputation::BEST_REPLY_AWARDED;

        $this->assertEquals($reputation, $reply->owner->reputation);
    }

    /** @test */
    public function a_user_earns_points_when_their_reply_is_favorited()
    {
        $this->signIn();
        $thread = create('App\Thread');

        $reply = $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Here is a reply.'
        ]);

        $this->post(route('replies.favorite', $reply->id));

        $reputation = Reputation::REPLY_POSTED + Reputation::REPLY_FAVORITED;

        $this->assertEquals($reputation, $reply->owner->fresh()->reputation);
        $this->assertEquals(0, auth()->user()->reputation);
    }

    /** @test */
    public function a_user_loses_points_when_their_favorited_reply_is_unfavorited()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => create('App\User')->id]);

        $this->post(route('replies.favorite', $reply->id));

        $reputation = Reputation::REPLY_POSTED + Reputation::REPLY_FAVORITED;

        $this->assertEquals($reputation, $reply->owner->fresh()->reputation);

        $this->delete(route('replies.unfavorite', $reply->id));

        $reputation = (Reputation::REPLY_POSTED + Reputation::REPLY_FAVORITED)
                        - Reputation::REPLY_FAVORITED;

        $this->assertEquals($reputation, $reply->owner->fresh()->reputation);
        $this->assertEquals(0, auth()->user()->reputation);
    }
}
