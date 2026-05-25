<?php

namespace App\OpeningHours\Controllers;

use App\OpeningHours\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminHolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::orderBy('date')->get();
        return inertia('Admin/Holidays/Index', ['holidays' => $holidays]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|unique:holidays,date',
            'name' => 'required|string|max:255',
        ]);

        Holiday::create($validated);

        return redirect()->back()->with('success', 'Feiertag hinzugefügt.');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return redirect()->back()->with('success', 'Feiertag entfernt.');
    }
}
