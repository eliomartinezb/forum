<?php

namespace App;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Thread
 *
 * @property int $id
 * @property int $user_id
 * @property int $channel_id
 * @property-read int|null $replies_count
 * @property string $title
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Activity[] $activity
 * @property-read int|null $activity_count
 * @property-read \App\Channel $channel
 * @property-read \App\User $creator
 * @property-read bool $is_subscribed_to
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Reply[] $replies
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ThreadSubscription[] $subscriptions
 * @property-read int|null $subscriptions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Thread filters($filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Thread newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Thread newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Thread query()
 * @method static \Illuminate\Database\Eloquent\Builder|Thread whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thread whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thread whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thread whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thread whereRepliesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thread whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thread whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thread whereUserId($value)
 * @mixin \Eloquent
 */
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

    public function replies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Reply');
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * @throws \Exception
     */
    public function addReply($reply): Model
    {
        (new Spam())->detect($reply["body"]);
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

    public function channel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
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

    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
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
