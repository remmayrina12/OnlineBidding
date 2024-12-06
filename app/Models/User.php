<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */

    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
        'status',
        'suspension_until'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function info()
    {
        return $this->hasOne(UserInfo::class, 'user_id', 'id');
    }

    public function auctioneer()
    {
        return $this->hasMany(User::class, 'auctioneer_id');
    }

    public function bidder()
    {
        return $this->hasMany(User::class, 'bidder_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function ratingsReceived()
    {
        return $this->hasMany(Rating::class, 'user_id');
    }

    public function ratingsGiven()
    {
        return $this->hasMany(Rating::class, 'rater_id');
    }

    public function getAverageRatingAttribute()
    {
        return $this->ratingsReceived()->avg('stars');
    }

    public function routeNotificationForSms()
    {
        return $this->info->phone_number ?? null; // Ensure `phone_number` exists in `user_infos` table
    }

}
