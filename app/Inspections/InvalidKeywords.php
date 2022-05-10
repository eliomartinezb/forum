<?php

namespace App\Inspections;

Use Exception;

class InvalidKeywords implements SpamInterface
{
    protected $keywords = [
        'yahoo customer support'
    ];

    public function detect($body)
    {
        foreach ($this->keywords as $keyword) {
            if (stripos($body, $keyword) !== false) {
                throw new Exception('Your reply contains spam');
            }
        }
    }
}
