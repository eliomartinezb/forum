<?php

namespace Tests\Unit;

use App\Inspections\Spam;
use Exception;
use PHPUnit\Framework\TestCase;

class SpamTest extends TestCase
{
    /**
     * @test
     */
    public function it_validates_is_not_spam()
    {
        $spam = new Spam();
        $this->assertFalse($spam->detect('Innocent reply here'));
    }

    /**
     * @test
     * @throws Exception
     */
    public function it_validates_is_spam()
    {
        $spam = new Spam();
        $this->expectException(Exception::class);
        $spam->detect('yahoo customer support');
    }
}
