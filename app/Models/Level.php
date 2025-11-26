<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    /** Mass assignable attributes */
    protected $fillable = ['name', 'min_wpm', 'max_wpm', 'badge_color'];

    /** Relationships */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
