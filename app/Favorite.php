<?php

namespace App;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Favorite
 *
 * @property int $id
 * @property int $user_id
 * @property int $favorited_id
 * @property string $favorited_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Activity[] $activity
 * @property-read int|null $activity_count
 * @property-read Model|\Eloquent $favorited
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite query()
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereFavoritedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereFavoritedType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Favorite whereUserId($value)
 * @mixin \Eloquent
 */
class Favorite extends Model
{

    use RecordsActivity;

    protected $guarded = [];

    public function favorited()
    {
        return $this->morphTo();
    }
}
