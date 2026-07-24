<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;

class PomodoroTimer extends Component
{
    public $category = 'Belajar';
    public $description = 'Fokus Sesi Pomodoro';
    public $showModal = false;
    
    protected $rules = [
        'category' => 'required|string',
        'description' => 'nullable|string|max:255',
    ];

    public function completeSession($minutes, $category, $description)
    {
        $minutes = (int) $minutes;
        if ($minutes <= 0) return;

        $user = Auth::user();
        
        // Save activity automatically
        Activity::create([
            'user_id' => $user->id,
            'name' => $description ?: 'Pomodoro Session (' . $minutes . ' min)',
            'category' => $category ?: 'Belajar',
            'duration_minutes' => $minutes,
            'date' => date('Y-m-d'),
        ]);

        // Add XP
        $oldLevel = $user->level;
        $user->addXp($minutes);
        $user->refresh();

        $isLevelUp = $user->level > $oldLevel;

        $this->dispatch('activity-saved');

        // Dispatch browser events for sounds & confetti
        $this->dispatch('pomodoro-completed', [
            'minutes' => $minutes,
            'isLevelUp' => $isLevelUp,
            'newLevel' => $user->level,
        ]);
    }

    public function render()
    {
        return view('livewire.pomodoro-timer');
    }
}
