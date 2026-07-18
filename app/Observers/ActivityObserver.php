<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Badge;

class ActivityObserver
{
    public function created(Activity $activity): void
    {
        $user = $activity->user;

        // 1. Add XP
        $user->addXp($activity->duration_minutes);

        // 2. Check and Award Badges
        $this->checkFirstActivityBadge($user);
        $this->checkMarathonBadge($user, $activity);
    }

    protected function checkFirstActivityBadge($user)
    {
        if ($user->activities()->count() === 1) {
            $badge = Badge::where('condition', 'first_activity')->first();
            if ($badge && !$user->badges->contains($badge->id)) {
                $user->badges()->attach($badge->id);
            }
        }
    }

    protected function checkMarathonBadge($user, $activity)
    {
        // 3 hours = 180 minutes
        if ($activity->duration_minutes >= 180) {
            $badge = Badge::where('condition', 'marathon')->first();
            if ($badge && !$user->badges->contains($badge->id)) {
                $user->badges()->attach($badge->id);
            }
        }
    }
}
