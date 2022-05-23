<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'name';
    }

    public function threads(): HasMany
    {
        return $this->hasMany('App\Thread')->latest();
    }

    /**
     * Fetch the last published reply for the user.
     *
     * @return HasOne
     */
    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    public function activity(): HasMany
    {
        return $this->hasMany('App\Activity');
    }

    public function visitedThreadCacheKey(Thread $thread): string
    {
        return sprintf("users.%s.thread.%s", $this->id, $thread->id);
    }

    /**
     * @throws \Exception
     */
    public function readThread(Thread $thread)
    {
        cache()->forever(
            $this->visitedThreadCacheKey($thread),
            Carbon::now()
        );
    }
}
