<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\CategoryTarget;
use Illuminate\Validation\Rule;

class CategoryTargetController extends Controller
{
    public function index()
    {
        $targets = Auth::user()->categoryTargets()
            ->where('month', date('n'))
            ->where('year', date('Y'))
            ->get();
        $categories = CategoryTarget::CATEGORIES;
        return view('category-targets.index', compact('targets', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => [
                'required',
                'string',
                Rule::in(CategoryTarget::CATEGORIES),
                Rule::unique('category_targets')->where(function ($query) {
                    return $query->where('user_id', Auth::id())
                                 ->where('month', date('n'))
                                 ->where('year', date('Y'));
                }),
            ],
            'target_days' => 'required|integer|min:1|max:31',
            'target_days_of_week' => 'nullable|array',
            'target_days_of_week.*' => 'string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'minimum_hours_per_day' => 'nullable|numeric|min:0',
        ]);

        Auth::user()->categoryTargets()->create([
            'category' => $request->category,
            'target_days' => $request->target_days,
            'target_days_of_week' => $request->target_days_of_week,
            'minimum_hours_per_day' => $request->minimum_hours_per_day,
            'month' => date('n'),
            'year' => date('Y')
        ]);

        return redirect()->route('category-targets.index')->with('success', 'Category target created successfully.');
    }

    public function update(Request $request, CategoryTarget $categoryTarget)
    {
        if ($categoryTarget->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'category' => [
                'required',
                'string',
                Rule::in(CategoryTarget::CATEGORIES),
                Rule::unique('category_targets')->where(function ($query) use ($categoryTarget) {
                    return $query->where('user_id', Auth::id())
                                 ->where('month', $categoryTarget->month)
                                 ->where('year', $categoryTarget->year);
                })->ignore($categoryTarget->id),
            ],
            'target_days' => 'required|integer|min:1|max:31',
            'target_days_of_week' => 'nullable|array',
            'target_days_of_week.*' => 'string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'minimum_hours_per_day' => 'nullable|numeric|min:0',
        ]);

        $categoryTarget->update($request->only('category', 'target_days', 'target_days_of_week', 'minimum_hours_per_day'));

        return redirect()->route('category-targets.index')->with('success', 'Category target updated successfully.');
    }

    public function destroy(CategoryTarget $categoryTarget)
    {
        if ($categoryTarget->user_id !== Auth::id()) {
            abort(403);
        }

        $categoryTarget->delete();

        return redirect()->route('category-targets.index')->with('success', 'Category target deleted successfully.');
    }
}
