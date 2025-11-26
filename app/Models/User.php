<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /** Mass assignable attributes */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'level_id',
        'last_login_at',
    ];

    /** Hidden attributes */
    protected $hidden = ['password', 'remember_token'];

    /** Attribute casting */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
    ];

    /** Relationships */
    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function attempts()
    {
        return $this->hasMany(Attempt::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
                    ->withTimestamps()
                    ->withPivot('earned_at');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }

    public function stats()
    {
        return $this->hasMany(UserStat::class);
    }

    /** Helper methods */
    public function bestAttempt()
    {
        return $this->attempts()->orderByDesc('wpm')->first();
    }

    public function averageWpm()
    {
        return round($this->attempts()->avg('wpm'), 2);
    }

    public function averageAccuracy()
    {
        return round($this->attempts()->avg('accuracy'), 2);
    }
}
