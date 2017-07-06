<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guests_may_not_create_threads()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $thread = make('App\Thread');

        $this->post('/threads', $thread->toArray());
    }

    public function guests_cannot_see_the_create_threads_page()
    {
        $this->withExceptionHandling()
            ->get('/threads/create')
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        // Given we have a signed in user
        $this->signIn();

        // When we hit the enpoint to create a new thread
        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray());

        // Then, when we visit the thread page
        $this->get($thread->path())

        // We should see the new thread
        ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
