<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use App\Models\Activity;
use Illuminate\Support\Carbon;

class ActivityHistory extends Component
{
    use WithPagination;

    public $editingId = null;
    public $editDate;
    public $editStartTime;
    public $editEndTime;
    public $editCategory;
    public $editDescription;
    public $categories = \App\Models\CategoryTarget::CATEGORIES;

    public function deleteActivity($id)
    {
        $activity = Activity::where('user_id', Auth::id())->find($id);
        if ($activity) {
            $activity->delete();
            $this->dispatch('activity-saved');
        }
    }

    public function editActivity($id)
    {
        $activity = Activity::where('user_id', Auth::id())->find($id);
        if ($activity) {
            $this->editingId = $activity->id;
            $this->editDate = $activity->date;
            $this->editStartTime = Carbon::parse($activity->start_time)->format('H:i');
            $this->editEndTime = Carbon::parse($activity->end_time)->format('H:i');
            $this->editCategory = $activity->category;
            $this->editDescription = $activity->description;
        }
    }

    public function cancelEdit()
    {
        $this->editingId = null;
    }

    public function updateActivity()
    {
        $this->validate([
            'editDate' => 'required|date',
            'editStartTime' => 'required',
            'editEndTime' => 'required',
            'editCategory' => 'required|string',
            'editDescription' => 'nullable|string',
        ]);

        $activity = Activity::where('user_id', Auth::id())->find($this->editingId);
        if ($activity) {
            $start = Carbon::parse($this->editDate . ' ' . $this->editStartTime);
            $end = Carbon::parse($this->editDate . ' ' . $this->editEndTime);
            
            if ($end->lessThan($start)) {
                $end->addDay();
            }

            $activity->update([
                'date' => $this->editDate,
                'start_time' => $this->editStartTime,
                'end_time' => $this->editEndTime,
                'duration_minutes' => $start->diffInMinutes($end),
                'category' => $this->editCategory,
                'description' => $this->editDescription,
            ]);

            $this->editingId = null;
            $this->dispatch('activity-saved');
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $activities = Auth::user()->activities()
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(15);

        // Fetch all category targets for the user
        $categoryTargets = Auth::user()->categoryTargets()->get();

        // Calculate current month overall plan achievement
        $currentMonthTargets = Auth::user()->categoryTargets()
            ->where('month', date('n'))
            ->where('year', date('Y'))
            ->get();
            
        $totalPercentage = 0;
        $targetCount = $currentMonthTargets->count();
        
        if ($targetCount > 0) {
            $sumPercentages = 0;
            foreach ($currentMonthTargets as $target) {
                $uniqueDays = Auth::user()->activities()
                    ->whereMonth('date', date('n'))
                    ->whereYear('date', date('Y'))
                    ->where('category', $target->category)
                    ->distinct('date')
                    ->count('date');
                
                $actualPercentage = $target->target_days > 0 ? ($uniqueDays / $target->target_days) * 100 : 0;
                $sumPercentages += min($actualPercentage, 100);
            }
            $totalPercentage = round($sumPercentages / $targetCount, 1);
        }

        return view('livewire.activity-history', [
            'activities' => $activities,
            'categoryTargets' => $categoryTargets,
            'totalPercentage' => $totalPercentage,
            'hasTargets' => $targetCount > 0,
        ]);
    }
}
