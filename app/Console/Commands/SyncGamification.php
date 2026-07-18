<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Badge;
use Illuminate\Support\Facades\DB;

class SyncGamification extends Command
{
    protected $signature = 'gamification:sync';
    protected $description = 'Sync gamification data (XP, level, badges) from existing activities';

    public function handle()
    {
        $this->info("Mereset data Gamifikasi...");
        User::query()->update(['xp' => 0, 'level' => 1]);
        DB::table('user_badges')->truncate();

        $users = User::all();
        foreach ($users as $user) {
            $this->info("Memproses User: {$user->email}");
            $activities = $user->activities()->orderBy('created_at')->get();

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
            $this->info("Selesai: Level {$user->level}, XP {$user->xp}");
        }

        $this->info("Sinkronisasi selesai.");
    }
}
