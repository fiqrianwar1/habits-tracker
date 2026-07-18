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
}
