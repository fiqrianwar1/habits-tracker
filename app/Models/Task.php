<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'date', 'is_completed'];

    protected $casts = [
        'date' => 'date',
        'is_completed' => 'boolean',
    ];
}
