<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'xp', 'level'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function categoryTargets()
    {
        return $this->hasMany(CategoryTarget::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')->withTimestamps();
    }

    public function addXp($minutes)
    {
        $xpGained = (int) round(($minutes / 60) * 10);
        if ($xpGained <= 0) return;

        $this->xp += $xpGained;

        // check level
        while (true) {
            $requiredXpForNextLevel = $this->level * 100;
            $xpAtStartOfLevel = ($this->level * ($this->level - 1) / 2) * 100;
            
            $xpInCurrentLevel = $this->xp - $xpAtStartOfLevel;
            
            if ($xpInCurrentLevel >= $requiredXpForNextLevel) {
                $this->level++;
            } else {
                break;
            }
        }
        $this->save();
    }

    public function getStreakAttribute(): array
    {
        $dates = $this->activities()
            ->selectRaw('DISTINCT date')
            ->orderBy('date', 'desc')
            ->pluck('date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
            ->toArray();

        if (empty($dates)) {
            return ['current' => 0, 'best' => 0];
        }

        $today = \Carbon\Carbon::today()->format('Y-m-d');
        $yesterday = \Carbon\Carbon::yesterday()->format('Y-m-d');

        $currentStreak = 0;
        $bestStreak = 0;
        $tempStreak = 0;

        $firstDate = $dates[0];
        if ($firstDate === $today || $firstDate === $yesterday) {
            $checkDate = \Carbon\Carbon::parse($firstDate);
            foreach ($dates as $dateStr) {
                $d = \Carbon\Carbon::parse($dateStr);
                $diff = $checkDate->diffInDays($d);
                if ($diff <= 1) {
                    $tempStreak++;
                    $checkDate = $d;
                } else {
                    break;
                }
            }
            $currentStreak = $tempStreak;
        }

        // Calculate best streak
        $bestStreak = max(1, $currentStreak);
        $tempStreak = 1;
        for ($i = 0; $i < count($dates) - 1; $i++) {
            $d1 = \Carbon\Carbon::parse($dates[$i]);
            $d2 = \Carbon\Carbon::parse($dates[$i + 1]);
            if ($d1->diffInDays($d2) == 1) {
                $tempStreak++;
                if ($tempStreak > $bestStreak) {
                    $bestStreak = $tempStreak;
                }
            } else {
                $tempStreak = 1;
            }
        }

        return [
            'current' => $currentStreak,
            'best' => $bestStreak,
        ];
    }
}
