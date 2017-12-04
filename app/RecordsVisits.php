<?php

namespace App;

use Illuminate\Support\Facades\Redis;

trait RecordsVisits
{
    /**
     * Increase visitor count.
     */
    public function recordVisit()
    {
        Redis::incr($this->visitsCacheKey());

        return $this;
    }

    /**
     * Delete the visitor count.
     */
    public function resetVisits()
    {
        Redis::del($this->visitsCacheKey());

        return $this;
    }

    /**
     * Get the total visits.
     * @return [type] [description]
     */
    public function visits()
    {
        return Redis::get($this->visitsCacheKey()) ?? 0;
    }

    /**
     * Visitor counter identifier.
     */
    public function visitsCacheKey()
    {
        return "threads.{$this->id}.visits";
    }
}
