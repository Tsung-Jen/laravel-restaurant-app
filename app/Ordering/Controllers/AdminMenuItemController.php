<?php

namespace App\Ordering\Controllers;

use App\Ordering\Models\Category;
use App\Ordering\Models\MenuItem;
use App\Ordering\Requests\StoreMenuItemRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class AdminMenuItemController
{
    public function index(Request $request): Response
    {
        $query = MenuItem::with('category');

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('item_number', 'like', "%{$search}%");
            });
        }

        return inertia('Admin/MenuItems/Index', [
            'menuItems' => $query->orderBy('sort_order')->paginate(20),
            'filters' => $request->only(['search']),
        ]);
    }

    public function create(): Response
    {
        return inertia('Admin/MenuItems/Form', [
            'categories' => Category::orderBy('sort_order')->get(),
        ]);
    }

    public function store(StoreMenuItemRequest $request): RedirectResponse
    {
        MenuItem::create($request->validated());

        return redirect()->route('admin.ordering.menu-items.index')
            ->with('success', __('messages.item_created'));
    }

    public function edit(MenuItem $menuItem): Response
    {
        return inertia('Admin/MenuItems/Form', [
            'menuItem' => $menuItem->load('category'),
            'categories' => Category::orderBy('sort_order')->get(),
        ]);
    }

    public function update(StoreMenuItemRequest $request, MenuItem $menuItem): RedirectResponse
    {
        $menuItem->update($request->validated());

        return redirect()->route('admin.ordering.menu-items.index')
            ->with('success', __('messages.item_updated'));
    }

    public function destroy(MenuItem $menuItem): RedirectResponse
    {
        $menuItem->delete();

        return redirect()->route('admin.ordering.menu-items.index')
            ->with('success', __('messages.item_deleted'));
    }
}
