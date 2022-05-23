<?php

namespace App\Policies;

use App\Reply;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Reply $reply)
    {
        return $reply->user_id == $user->id;
    }


    /**
     * Determine if the authenticated user has permission to create a new reply.
     *
     * @param  User $user
     * @return bool
     */
    public function create(User $user)
    {
        if (! $lastReply = $user->fresh()->lastReply) {
            return true;
        }

        return ! $lastReply->wasJustPublished();
    }
}
