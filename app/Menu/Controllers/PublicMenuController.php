<?php

namespace App\Menu\Controllers;

use Illuminate\Support\Facades\Storage;

class PublicMenuController
{
    public function show()
    {
        $menuPath = 'menu/speisekarte.pdf';
        $menuExists = Storage::disk('public')->exists($menuPath);

        return view('pages.menu', [
            'menuExists' => $menuExists,
            'menuUrl' => $menuExists ? '/storage/menu/speisekarte.pdf' : null,
        ]);
    }
}
