<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use CanBeFavorited;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['thread_id', 'user_id', 'body'];

    /**
     * Eager Load relationships in every single query.
     *
     * @var array
     */
    protected $with = ['owner', 'favorites'];

    /**
     * A reply has an owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
