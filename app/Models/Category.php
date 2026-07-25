<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'user_id',
        'name',
    ];

    public const DEFAULT_CATEGORIES = [
        'Kerja', 'Olahraga', 'Bersih-bersih', 'Belajar', 'Main Game', 'Family Time'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get default categories combined with user-defined custom categories.
     *
     * @param  \App\Models\User|int|null  $user
     * @return array
     */
    public static function getAllForUser($user = null): array
    {
        $userId = $user instanceof User ? $user->id : $user;

        $custom = [];
        if ($userId) {
            $custom = self::where('user_id', $userId)
                ->pluck('name')
                ->toArray();
        }

        // Merge defaults and custom categories, maintaining unique values
        return array_values(array_unique(array_merge(self::DEFAULT_CATEGORIES, $custom)));
    }
}
