<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GameScore extends Model
{
    use HasFactory;

    /** ✅ الحقول القابلة للتعبئة */
    protected $fillable = [
        'user_id',       // User reference (nullable)
        'player_name',   // Player name
        'wpm',           // Words per minute
        'accuracy',      // Accuracy percentage
        'game_type',     // Game type
    ];

    /** ✅ التحويلات */
    protected $casts = [
        'wpm' => 'integer',
        'accuracy' => 'float',
    ];

    /** ✅ العلاقة مع المستخدم */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** ✅ تسمية نوع اللعبة بالعربية */
    public function getGameTypeLabelAttribute(): string
    {
        return match ($this->game_type) {
            'speed'         => 'اختبار السرعة',
            'race'          => 'سباق الطباعة',
            'letters'       => 'لعبة الحروف',
            'random-words'  => 'اختبار الكلمات العشوائية',
            'arabic-typing' => 'اختبار سرعة الكتابة بالعربية',
            default         => 'غير معروف',
        };
    }

    /** ✅ تنسيق التاريخ */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }
}
