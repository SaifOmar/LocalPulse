<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use App\Enums\ImageTypeEnum;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $handle
 * @property string|null $bio
 * @property int $first
 * @property string|null $gender
 * @property string $avatar
 * @property int $user_id
 * @property int $num_followers
 * @property int $num_following
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int,
 * \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereFirst($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereHandle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereNumFollowers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereNumFollowing($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account withoutTrashed()
 * @mixin \Eloquent
 */
class Account extends Model
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'user_id',
        'deleted_at',
        'accounts',
    ];
    public function getNameAttribute() : string {
        return $this->user->name;
    }
    public  function getEmailAttribute(): string
    {
        return $this->user->email;
    }
    public function getAccountsAttribute()
    {
          return $this->user->accounts;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return string
     */
    public function getAvatarAttribute() : ?Image
    {
        return Image::where(
            [
                'account_id' => $this->id,
                'type' => ImageTypeEnum::AVATAR,
            ]
        )->first();
    }

    public function pulses(): void  {
        throw new \Exception("Not implemented yet");
    }
    public function images(): void  {
        throw new \Exception("Not implemented yet");
    }
}
