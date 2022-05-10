<?php

namespace App;

use App\Inspections\Spam;
use App\Traits\RecordsActivity;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    public static function boot()
    {
        parent::boot();

        // static::addGlobalScope('replyCount', function ($builder) {
        //     $builder->withCount('replies');
        // });

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });
    }

    public function path(): string
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function replies(): HasMany
    {
        return $this->hasMany('App\Reply');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * @throws \Exception
     */
    public function addReply($reply): Model
    {
        $reply = $this->replies()->create($reply);

        $this->notifySubscribers($reply);

        return $reply;
    }

    protected function notifySubscribers(Reply $reply)
    {
        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each
            ->notify($reply);
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo('App\Channel');
    }

    public function scopeFilters($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($user_id = null): Thread
    {
        $this->subscriptions()->create([
            'user_id' => $user_id ?: auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($user_id = null)
    {
        $this->subscriptions()
            ->where('user_id', $user_id ?: auth()->id())
            ->delete();
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute(): bool
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function hasUpdatesFor(User $user = null): bool
    {
        $user = $user ?: auth()->user();

        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at >cache($key);
    }
}
