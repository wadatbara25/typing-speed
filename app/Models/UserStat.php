<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStat extends Model
{
    use HasFactory;

    /** Mass assignable attributes */
    protected $fillable = [
        'user_id',
        'date',
        'avg_wpm',
        'avg_accuracy',
        'total_attempts',
    ];

    /** Attribute casting */
    protected $casts = [
        'date' => 'date',
    ];

    /** Relationships */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
