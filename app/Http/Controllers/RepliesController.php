<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Support\Facades\Gate;

class RepliesController extends Controller
{
    /**
     * Create a new RepliesController instance.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    /**
     * Fetch all relevant replies.
     *
     * @param int    $channelId
     * @param Thread $thread
     */
    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    /**
     * Persist a new reply.
     *
     * @param  integer $channelId
     * @param  Thread $thread
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread)
    {
        if (Gate::denies('create', new Reply)) {
            return response(
                'You are posting too frequently. Please take a break. :)', 429
            );
        }

        try {
            $this->validate(request(), ['body' => 'required|spamfree']);

            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
            ]);
        } catch(\Exception $e) {
            return response(
                'Sorry, your reply could not be saved at this time.', 422
            );
        }

        return $reply->load('owner');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Reply  $reply
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        try {
            $this->validate(request(), ['body' => 'required|spamfree']);

            $reply->update(request(['body']));
        } catch(\Exception $e) {
            return response(
                'Sorry, your reply could not be saved at this time.', 422
            );
        }
    }

    /**
     * Delete the given reply.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return back();
    }
}
