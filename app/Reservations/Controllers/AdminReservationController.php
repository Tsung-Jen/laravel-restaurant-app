<?php

namespace App\Reservations\Controllers;

use App\Reservations\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class AdminReservationController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Reservation::query();

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return inertia('Admin/Reservations/Index', [
            'reservations' => $query->latest()->paginate(20),
            'filters' => $request->only(['date', 'status']),
        ]);
    }

    public function updateStatus(Request $request, Reservation $reservation): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:open,confirmed,cancelled',
        ]);

        $reservation->update($validated);

        return back();
    }

    public function destroy(Reservation $reservation): RedirectResponse
    {
        $reservation->delete();

        return back();
    }
}
