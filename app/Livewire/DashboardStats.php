<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class DashboardStats extends Component
{
    public $yearlyData = [];
    public $categoryData = [];
    public $dailyData = [];
    public $targetData = [];
    public $currentMonthPercentage = 0;
    
    // Gamification & Heatmap
    public $userProfile = [];
    public $userBadges = [];
    public $heatmapData = [];

    public function mount()
    {
        $this->loadData();
    }

    #[On('activity-saved')] 
    public function loadData()
    {
        $user = Auth::user();
        $currentYear = date('Y');
        
        // --- Gamification & User Stats ---
        $currentLevel = $user->level ?? 1;
        $currentXp = $user->xp ?? 0;
        $xpAtStartOfLevel = ($currentLevel * ($currentLevel - 1) / 2) * 100;
        $xpRequiredForNextLevel = $currentLevel * 100;
        $xpInCurrentLevel = $currentXp - $xpAtStartOfLevel;
        // fallback division by zero just in case
        $xpRequiredForNextLevel = max(1, $xpRequiredForNextLevel);
        $progressPercentage = min(100, round(($xpInCurrentLevel / $xpRequiredForNextLevel) * 100));

        $streak = $user->streak;

        $this->userProfile = [
            'name' => $user->name,
            'level' => $currentLevel,
            'xp' => $currentXp,
            'xp_current_level' => $xpInCurrentLevel,
            'xp_required' => $xpRequiredForNextLevel,
            'progress' => $progressPercentage,
            'current_streak' => $streak['current'],
            'best_streak' => $streak['best'],
        ];

        $this->userBadges = $user->badges()->get();

        // --- Heatmap (365 hari terakhir) ---
        $oneYearAgo = date('Y-m-d', strtotime('-365 days'));
        
        $heatmapActivities = $user->activities()
            ->where('date', '>=', $oneYearAgo)
            ->selectRaw('date, SUM(duration_minutes) as total')
            ->groupBy('date')
            ->get()
            ->keyBy('date');
            
        $heatmapData = [];
        $currentDate = new \DateTime($oneYearAgo);
        $endDate = new \DateTime();
        
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $minutes = isset($heatmapActivities[$dateStr]) ? $heatmapActivities[$dateStr]->total : 0;
            
            $intensity = 0;
            if ($minutes > 0 && $minutes <= 30) $intensity = 1;
            elseif ($minutes > 30 && $minutes <= 90) $intensity = 2;
            elseif ($minutes > 90 && $minutes <= 180) $intensity = 3;
            elseif ($minutes > 180) $intensity = 4;
            
            $heatmapData[] = [
                'date' => $dateStr,
                'intensity' => $intensity,
                'minutes' => $minutes
            ];
            $currentDate->modify('+1 day');
        }
        $this->heatmapData = $heatmapData;

        // --- 1. Grafik Harian (7 Hari Terakhir) ---
        $daily = $user->activities()
            ->selectRaw('date, SUM(duration_minutes) as total')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(7)
            ->get()
            ->reverse()
            ->values();
        
        $this->dailyData = [
            'labels' => $daily->pluck('date')->toArray(),
            'data' => $daily->pluck('total')->map(fn($val) => round($val / 60, 1))->toArray()
        ];
        
        // 1. Grafik Tahunan (Diagram Batang 12 Bulan)
        $yearlyActivities = $user->activities()
            ->whereYear('date', $currentYear)
            ->get(['date', 'duration_minutes']);
        
        $yearlyTotals = array_fill(1, 12, 0);
        $totalYearMinutes = 0;
        $currentMonthMinutes = 0;
        $currentMonthNum = (int) date('n');

        foreach ($yearlyActivities as $activity) {
            $m = (int) \Carbon\Carbon::parse($activity->date)->format('n');
            $yearlyTotals[$m] += $activity->duration_minutes;
            $totalYearMinutes += $activity->duration_minutes;
            
            if ($m === $currentMonthNum) {
                $currentMonthMinutes += $activity->duration_minutes;
            }
        }

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $this->yearlyData = [
            'labels' => $months,
            'data' => array_map(fn($v) => round($v / 60, 1), array_values($yearlyTotals))
        ];
        
        $this->currentMonthPercentage = $totalYearMinutes > 0 
            ? round(($currentMonthMinutes / $totalYearMinutes) * 100, 1) 
            : 0;

        // 2. Grafik Bulanan berdasarkan kategori (Bulan ini)
        $monthly = $user->activities()
            ->whereMonth('date', date('m'))
            ->whereYear('date', $currentYear)
            ->selectRaw('category, SUM(duration_minutes) as total')
            ->groupBy('category')
            ->get();
            
        $this->categoryData = [
            'labels' => $monthly->pluck('category')->toArray(),
            'data' => $monthly->pluck('total')->map(fn($val) => round($val / 60, 1))->toArray()
        ];

        // 3. Data Target Kategori (Plan Kegiatan)
        $currentMonthTargets = $user->categoryTargets()
            ->where('month', date('n'))
            ->where('year', date('Y'))
            ->get();
            
        $totalMonthlyMinutes = $monthly->sum('total');
        
        $targetData = [];
        foreach ($currentMonthTargets as $target) {
            $uniqueDays = $user->activities()
                ->whereMonth('date', date('n'))
                ->whereYear('date', date('Y'))
                ->where('category', $target->category)
                ->distinct('date')
                ->count('date');
                
            $actualPercentage = $target->target_days > 0 ? round(($uniqueDays / $target->target_days) * 100, 1) : 0;
            
            $todayMinutes = $user->activities()
                ->where('date', date('Y-m-d'))
                ->where('category', $target->category)
                ->sum('duration_minutes');
            $todayHours = round($todayMinutes / 60, 2);
            
            $dailyPercentage = 0;
            if ($target->minimum_hours_per_day > 0) {
                $dailyPercentage = min(round(($todayHours / $target->minimum_hours_per_day) * 100, 1), 100);
            }
            
            $targetData[] = [
                'category' => $target->category,
                'target_days' => $target->target_days,
                'actual_days' => $uniqueDays,
                'actual_percentage' => min($actualPercentage, 100),
                'minimum_hours_per_day' => $target->minimum_hours_per_day,
                'today_hours' => $todayHours,
                'daily_percentage' => $dailyPercentage,
                'target_days_of_week' => $target->target_days_of_week,
            ];
        }
        $this->targetData = $targetData;

        $this->dispatch('stats-updated', [
            'yearlyData' => $this->yearlyData,
            'categoryData' => $this->categoryData,
            'dailyData' => $this->dailyData,
        ]);
    }

    public function render()
    {
        return view('livewire.dashboard-stats');
    }
}
