<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\ActivityObserver;

#[ObservedBy(ActivityObserver::class)]
class Activity extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time',
        'duration_minutes',
        'category',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
