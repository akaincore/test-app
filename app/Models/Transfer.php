<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Transfer
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transfer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transfer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transfer query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $sender_id
 * @property int $recipient_id
 * @property string $time
 * @property float $sum
 * @property int $completed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transfer whereCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transfer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transfer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transfer whereRecipientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transfer whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transfer whereSum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transfer whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transfer whereUpdatedAt($value)
 * @property int $with_error
 * @property-read \App\Models\User $recipient
 * @property-read \App\Models\User $sender
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transfer unprocessed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transfer whereWithError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transfer shouldBeProcessed()
 */
class Transfer extends Model
{

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'time',
        'sum',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnprocessed($query)
    {
        return $query->where('completed', 0)->where('with_error', 0);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeShouldBeProcessed($query)
    {
        return $query->where('time', '<=', Carbon::now()->toDateTimeString());
    }

}
