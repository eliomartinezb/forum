<?php

namespace App;

class Spam
{

    /**
     * @throws Exception
     */
    public function detect($body): bool
    {
        $this->detectInvalidKeywords($body);

        return false;
    }

    /**
     * @throws \Exception
     */
    private function detectInvalidKeywords($body)
    {
        $invalidKeywords = [
            strtolower('Yahoo Customer Support')
        ];

        if (in_array(strtolower($body), $invalidKeywords)) {
            throw new \Exception('Your reply contains spam');
        }
    }
}
