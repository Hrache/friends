<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Friend;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * All friends
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function allFriends()
    {
        $friends = $this->friends()->with('user')->get()->makeHidden([
            'current_team_id', 'created_at', 'updated_at'
        ]);

        $friends = $friends->merge(
            $this->friendsTo()->with('by_user')->get()->makeHidden([
                'current_team_id', 'created_at', 'updated_at'
            ])
        );

        return $friends;
    }

    /**
     * Friends
     */
    public function friends()
    {
        return $this->hasMany(Friend::class, 'user_id')->where('status', Friend::STATUS_APPROVED);
    }

    /**
     * Friends to
     */
    public function friendsTo()
    {
        return $this->hasMany(Friend::class, 'friend_id')->where('status', Friend::STATUS_APPROVED);
    }

    /**
     * Pending
     */
    public function pending()
    {
        return $this->hasMany(Friend::class, 'friend_id')->where('status', Friend::STATUS_PENDING);
    }

    /**
     * Pending
     */
    public function requested()
    {
        return $this->hasMany(Friend::class, 'user_id')->where('status', Friend::STATUS_PENDING);
    }
}
