<?php

use App\Models\User;
use App\Models\Badge;
use Illuminate\Support\Facades\DB;

echo "Mereset data Gamifikasi...\n";
User::query()->update(['xp' => 0, 'level' => 1]);
DB::table('user_badges')->truncate();

$users = User::all();
foreach ($users as $user) {
    echo "Memproses User: {$user->email}\n";
    $activities = $user->activities()->orderBy('created_at')->get();
    
    // First, let's debug a single activity date if exists
    if ($activities->count() > 0) {
        echo "Contoh tanggal aktivitas: " . $activities->first()->date . "\n";
    }

    foreach ($activities as $index => $activity) {
        // add XP
        $xpGained = (int) round(($activity->duration_minutes / 60) * 10);
        if ($xpGained > 0) {
            $user->xp += $xpGained;
        }

        // check first activity badge
        if ($index === 0) {
            $badge = Badge::where('condition', 'first_activity')->first();
            if ($badge && !$user->badges->contains($badge->id)) {
                $user->badges()->attach($badge->id);
            }
        }

        // check marathon badge
        if ($activity->duration_minutes >= 180) {
            $badge = Badge::where('condition', 'marathon')->first();
            if ($badge && !$user->badges->contains($badge->id)) {
                $user->badges()->attach($badge->id);
            }
        }
    }
    
    // calculate level
    $user->level = 1;
    while (true) {
        $requiredXpForNextLevel = $user->level * 100;
        $xpAtStartOfLevel = ($user->level * ($user->level - 1) / 2) * 100;
        
        $xpInCurrentLevel = $user->xp - $xpAtStartOfLevel;
        
        if ($xpInCurrentLevel >= $requiredXpForNextLevel) {
            $user->level++;
        } else {
            break;
        }
    }
    
    $user->save();
    echo "Selesai: Level {$user->level}, XP {$user->xp}\n";
}

echo "Sinkronisasi selesai.\n";
