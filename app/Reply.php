<?php

namespace App;

use App\Traits\Favoritable;
use App\Traits\RecordsActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Reply
 *
 * @property int $id
 * @property int $user_id
 * @property int $thread_id
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection|\App\Activity[] $activity
 * @property-read int|null $activity_count
 * @property-read Collection|\App\Favorite[] $favorites
 * @property-read int|null $favorites_count
 * @property-read mixed $is_favorited
 * @property-read \App\User $owner
 * @property-read \App\Thread $thread
 * @method static \Illuminate\Database\Eloquent\Builder|Reply newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reply newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reply query()
 * @method static \Illuminate\Database\Eloquent\Builder|Reply whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reply whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reply whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reply whereThreadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reply whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reply whereUserId($value)
 * @mixin \Eloquent
 */
class Reply extends Model
{

    use Favoritable, RecordsActivity;

    protected $guarded = [];

    protected $with = ['owner', 'favorites'];

    protected $appends = ['favoritesCount', 'isFavorited'];

    public static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            $reply->thread->increment('replies_count');
        });

        static::deleted(function ($reply) {
            $reply->thread->decrement('replies_count');
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function thread(): BelongsTo
    {
        return $this->belongsTo('App\Thread');
    }

    public function path(): string
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }

    /**
     * Determine if the reply was just published a moment ago.
     *
     * @return bool
     */
    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    public function mentionedUsers() {
        preg_match_all('/(?<=@)[^\s\.@]+/', $this->body, $matches);

        return $matches[0];
    }
}
