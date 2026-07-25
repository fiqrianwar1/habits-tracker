<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\CategoryTarget;
use Illuminate\Validation\Rule;

use App\Models\Category;

class CategoryTargetController extends Controller
{
    public function index()
    {
        $targets = Auth::user()->categoryTargets()
            ->where('month', date('n'))
            ->where('year', date('Y'))
            ->get();
        $categories = Category::getAllForUser(Auth::user());
        $customCategories = Category::where('user_id', Auth::id())->get();

        return view('category-targets.index', compact('targets', 'categories', 'customCategories'));
    }

    public function store(Request $request)
    {
        $categories = Category::getAllForUser(Auth::user());

        $request->validate([
            'category' => [
                'required',
                'string',
                Rule::in($categories),
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

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $name = trim($request->name);
        Category::firstOrCreate([
            'user_id' => Auth::id(),
            'name' => $name,
        ]);

        return redirect()->route('category-targets.index')->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    public function updateCategory(Request $request, Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $newName = trim($request->name);
        $oldName = $category->name;

        if ($oldName !== $newName) {
            $category->update(['name' => $newName]);

            // Sync old category name in activities and category_targets
            Auth::user()->activities()->where('category', $oldName)->update(['category' => $newName]);
            Auth::user()->categoryTargets()->where('category', $oldName)->update(['category' => $newName]);
        }

        return redirect()->route('category-targets.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroyCategory(Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }

        $category->delete();

        return redirect()->route('category-targets.index')->with('success', 'Kategori berhasil dihapus.');
    }

    public function update(Request $request, CategoryTarget $categoryTarget)
    {
        if ($categoryTarget->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::getAllForUser(Auth::user());

        $request->validate([
            'category' => [
                'required',
                'string',
                Rule::in($categories),
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
