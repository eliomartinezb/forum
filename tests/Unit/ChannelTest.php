<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_channel_consists_on_threads()
    {
        $channel = create('App\Channel');
        $thread = create('App\Thread', [ 'channel_id' => $channel->id ]);

        $this->assertTrue($channel->threads->contains($thread));
    }
}