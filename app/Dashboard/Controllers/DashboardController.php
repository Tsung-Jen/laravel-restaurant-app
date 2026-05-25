<?php

namespace App\Dashboard\Controllers;

use App\Contact\Models\Contact;
use App\Reservations\Models\Reservation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class DashboardController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index()
    {
        $todayReservations = Reservation::whereDate('date', today())->count();
        $pendingReservations = Reservation::where('status', 'open')->count();
        $unreadContacts = Contact::whereNull('read_at')->count();
        $upcomingReservations = Reservation::whereDate('date', '>=', today())
            ->where('status', '!=', 'cancelled')
            ->latest('date')
            ->take(10)
            ->get();

        return inertia('Admin/Dashboard', [
            'stats' => [
                'today_reservations' => $todayReservations,
                'pending_reservations' => $pendingReservations,
                'unread_contacts' => $unreadContacts,
            ],
            'upcoming_reservations' => $upcomingReservations,
        ]);
    }
}
