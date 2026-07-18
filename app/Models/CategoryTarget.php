<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryTarget extends Model
{
    protected $fillable = [
        'user_id',
        'category',
        'target_percentage',
        'month',
        'year',
        'target_days',
        'target_days_of_week',
        'minimum_hours_per_day',
    ];

    protected $casts = [
        'target_days_of_week' => 'array',
        'minimum_hours_per_day' => 'decimal:2',
    ];

    public const CATEGORIES = [
        'Kerja', 'Olahraga', 'Bersih-bersih', 'Belajar', 'Main Game', 'Family Time'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
