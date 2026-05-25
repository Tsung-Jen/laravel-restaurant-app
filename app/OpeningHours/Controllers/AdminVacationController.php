<?php

namespace App\OpeningHours\Controllers;

use App\OpeningHours\Models\Vacation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminVacationController extends Controller
{
    public function index()
    {
        $vacations = Vacation::orderBy('start_date')->get();
        return inertia('Admin/Vacations/Index', ['vacations' => $vacations]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:255',
        ]);

        Vacation::create($validated);

        return redirect()->back()->with('success', 'Urlaub hinzugefügt.');
    }

    public function destroy(Vacation $vacation)
    {
        $vacation->delete();
        return redirect()->back()->with('success', 'Urlaub entfernt.');
    }
}
