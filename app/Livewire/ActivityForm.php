<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ActivityForm extends Component
{
    public $date;
    public $start_time;
    public $end_time;
    public $category = 'Kerja';
    public $description = '';

    public $categories = \App\Models\CategoryTarget::CATEGORIES;

    public function mount()
    {
        $now = Carbon::now()->timezone('Asia/Jakarta');
        $this->date = $now->format('Y-m-d');
        $this->start_time = $now->format('H:i');
        $this->end_time = $now->addHour()->format('H:i');
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

        $durationMinutes = $end->diffInMinutes($start);

        Auth::user()->activities()->create([
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'duration_minutes' => $durationMinutes,
            'category' => $this->category,
            'description' => $this->description,
        ]);

        $this->reset(['description']);
        $this->start_time = Carbon::now()->format('H:i');
        $this->end_time = Carbon::now()->addHour()->format('H:i');
        
        session()->flash('message', 'Kegiatan berhasil dicatat!');
        $this->dispatch('activity-saved');
    }

    public function render()
    {
        return view('livewire.activity-form');
    }
}
