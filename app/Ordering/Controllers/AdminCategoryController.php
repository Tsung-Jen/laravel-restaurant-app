<?php

namespace App\Ordering\Controllers;

use App\Ordering\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class AdminCategoryController
{
    public function index(): Response
    {
        return inertia('Admin/Categories/Index', [
            'categories' => Category::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        Category::create($validated);

        return redirect()->route('admin.ordering.categories.index')
            ->with('success', __('messages.category_created'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $category->update($validated);

        return redirect()->route('admin.ordering.categories.index')
            ->with('success', __('messages.category_updated'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->menuItems()->exists()) {
            return redirect()->route('admin.ordering.categories.index')
                ->with('error', __('messages.category_has_items'));
        }

        $category->delete();

        return redirect()->route('admin.ordering.categories.index')
            ->with('success', __('messages.category_deleted'));
    }
}
