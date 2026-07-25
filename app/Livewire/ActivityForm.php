<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Models\Category;

class ActivityForm extends Component
{
    public $date;
    public $start_time;
    public $end_time;
    public $category = 'Kerja';
    public $description = '';
    public $newCategoryName = '';
    public $showNewCategoryInput = false;

    public function mount()
    {
        $now = Carbon::now()->timezone('Asia/Jakarta');
        $this->date = $now->format('Y-m-d');
        $this->start_time = $now->format('H:i');
        $this->end_time = $now->addHour()->format('H:i');
        
        $categories = Category::getAllForUser(Auth::user());
        if (!in_array($this->category, $categories)) {
            $this->category = $categories[0] ?? 'Kerja';
        }
    }

    public function addCategory()
    {
        $this->validate([
            'newCategoryName' => 'required|string|max:50',
        ]);

        $name = trim($this->newCategoryName);
        if ($name) {
            Category::firstOrCreate([
                'user_id' => Auth::id(),
                'name' => $name,
            ]);
            $this->category = $name;
            $this->newCategoryName = '';
            $this->showNewCategoryInput = false;
            $this->dispatch('category-added');
        }
    }

    public function save()
    {
        $this->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'category' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $start = Carbon::parse($this->date . ' ' . $this->start_time);
        $end = Carbon::parse($this->date . ' ' . $this->end_time);
        
        // Handling next day end_time if it crosses midnight
        if ($end->lessThan($start)) {
            $end->addDay();
        }

        $durationMinutes = $start->diffInMinutes($end);

        $user = Auth::user();
        $oldLevel = $user->level;

        $user->activities()->create([
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'duration_minutes' => $durationMinutes,
            'category' => $this->category,
            'description' => $this->description,
        ]);

        $user->addXp($durationMinutes);
        $user->refresh();
        $isLevelUp = $user->level > $oldLevel;

        $this->reset(['description']);
        $this->start_time = Carbon::now()->format('H:i');
        $this->end_time = Carbon::now()->addHour()->format('H:i');
        
        session()->flash('message', 'Kegiatan berhasil dicatat! (+XP)');
        $this->dispatch('activity-saved');
        $this->dispatch('activity-added', [
            'isLevelUp' => $isLevelUp,
        ]);
    }

    public function render()
    {
        $categories = Category::getAllForUser(Auth::user());
        return view('livewire.activity-form', [
            'categories' => $categories,
        ]);
    }
}
