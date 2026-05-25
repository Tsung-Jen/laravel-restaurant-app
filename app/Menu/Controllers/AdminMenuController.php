<?php

namespace App\Menu\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminMenuController
{
    public function edit()
    {
        $menuPath = 'menu/speisekarte.pdf';
        $menuExists = Storage::disk('public')->exists($menuPath);

        return inertia('Admin/Menu/Edit', [
            'menuExists' => $menuExists,
            'menuUrl' => $menuExists ? '/storage/menu/speisekarte.pdf' : null,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'menu' => 'required|file|mimes:pdf|max:10240',
        ]);

        Storage::disk('public')->putFileAs('menu', $validated['menu'], 'speisekarte.pdf');

        return redirect()->back()->with('success', __('messages.menu_updated'));
    }

    public function destroy()
    {
        Storage::disk('public')->delete('menu/speisekarte.pdf');

        return redirect()->back()->with('success', __('messages.menu_deleted'));
    }
}
