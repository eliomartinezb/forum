<?php

namespace App;

use Exception;

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
     * @throws Exception
     */
    private function detectInvalidKeywords($body)
    {
        $invalidKeywords = [
            'Yahoo Customer Support'
        ];

        if (in_array($body, $invalidKeywords)) {
            throw new Exception('Your reply contains spam');
        }
    }
}
