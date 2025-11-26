<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    /** Table name */
    protected $table = 'articles';

    /** Mass assignable attributes */
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'content',
        'image',
        'status',
        'published_at',
        'author_name',
    ];

    /** Date fields */
    protected $dates = [
        'published_at',
        'created_at',
        'updated_at',
    ];

    /** Default attribute values */
    protected $attributes = [
        'status' => 'draft',
    ];

    /** Scope: only published articles */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /** Accessor: article URL */
    public function getUrlAttribute()
    {
        return route('article.show', $this->slug ?? $this->id);
    }
}
