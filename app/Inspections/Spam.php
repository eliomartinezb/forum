<?php

namespace App\Inspections;

use Exception;

class Spam implements SpamInterface
{
    protected $inspections = [
        InvalidKeywords::class,
        KeyHeldDown::class
    ];

    /**
     * @throws Exception
     */
    public function detect($body): bool
    {
        foreach ($this->inspections as $inspection) {
            app($inspection)->detect($body);
        }

        return false;
    }
}
