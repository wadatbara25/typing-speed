<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    use HasFactory;

    /** Mass assignable attributes */
    protected $fillable = [
        'user_id',
        'lesson_id',
        'wpm',
        'raw_wpm',
        'accuracy',
        'errors_json',
        'duration_seconds',
        'typed_text',
        'started_at',
        'finished_at',
    ];

    /** Attribute casting */
    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'errors_json' => 'array',
    ];

    /** Relationships */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /** Auto-calculate fields before saving */
    protected static function booted()
    {
        static::creating(function ($attempt) {
            if (!$attempt->finished_at && $attempt->started_at) {
                $attempt->finished_at = now();
            }

            if (!$attempt->duration_seconds && $attempt->started_at && $attempt->finished_at) {
                $attempt->duration_seconds = $attempt->finished_at->diffInSeconds($attempt->started_at);
            }
        });
    }
}
