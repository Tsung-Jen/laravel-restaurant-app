<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController
{
    public function switch(string $locale, Request $request)
    {
        if (! in_array($locale, ['de', 'en', 'zh_CN'])) {
            $locale = 'en';
        }

        session()->put('locale', $locale);

        return redirect()->back();
    }
}
