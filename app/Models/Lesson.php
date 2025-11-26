<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    /** Mass assignable attributes */
    protected $fillable = [
        'title',
        'content',
        'level',
        'duration_limit',
    ];

    /** Relationships */
    public function attempts()
    {
        return $this->hasMany(Attempt::class);
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }
}
