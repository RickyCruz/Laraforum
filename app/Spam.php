<?php

namespace App;

class Spam
{
    /**
     * Detect spam.
     *
     * @param  string $body
     * @throws \Exception
     */
    public function detect($body)
    {
        $this->detectInvalidKeywords($body);

        return false;
    }

    /**
     * Recognize all registered invalid keywords in reply.
     * @param  string $body
     * @throws \Exception
     */
    protected function detectInvalidKeywords($body)
    {
        $invalidKeywords = [
            'yahoo customer support'
        ];

        foreach ($invalidKeywords as $keyword) {
            if (stripos(request('body'), $keyword) !== false) {
                throw new \Exception('Your reply contains spam.');
            }
        }
    }
}
