<?php

namespace App\OpeningHours\Controllers;

use App\OpeningHours\Models\OpeningHour;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminOpeningHoursController extends Controller
{
    public function index()
    {
        $hours = OpeningHour::orderBy('day_of_week')->get();
        return inertia('Admin/OpeningHours/Index', ['hours' => $hours]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'hours' => 'required|array',
            'hours.*.day_of_week' => 'required|integer|between:0,6',
            'hours.*.lunch_start' => 'nullable|date_format:H:i',
            'hours.*.lunch_end' => 'nullable|date_format:H:i|required_with:hours.*.lunch_start',
            'hours.*.evening_start' => 'nullable|date_format:H:i',
            'hours.*.evening_end' => 'nullable|date_format:H:i|required_with:hours.*.evening_start',
            'hours.*.is_closed' => 'boolean',
            'hours.*.closed_except_week' => 'nullable|string|regex:/^\d{4}-W\d{2}$/',
        ]);

        foreach ($validated['hours'] as $hour) {
            OpeningHour::updateOrCreate(
                ['day_of_week' => $hour['day_of_week']],
                [
                    'lunch_start' => $hour['lunch_start'] ?? null,
                    'lunch_end' => $hour['lunch_end'] ?? null,
                    'evening_start' => $hour['evening_start'] ?? null,
                    'evening_end' => $hour['evening_end'] ?? null,
                    'is_closed' => $hour['is_closed'] ?? false,
                    'closed_except_week' => $hour['closed_except_week'] ?? null,
                ]
            );
        }

        return redirect()->back()->with('success', 'Öffnungszeiten aktualisiert.');
    }
}
