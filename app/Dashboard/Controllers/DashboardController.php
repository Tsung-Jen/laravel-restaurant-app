<?php

namespace App\Dashboard\Controllers;

use App\Contact\Models\Contact;
use App\Reservations\Models\Reservation;
use App\Reservations\Services\ReservationService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class DashboardController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index()
    {
        $service = app(ReservationService::class);
        $today = today()->format('Y-m-d');

        $todayReservations = Reservation::whereDate('date', $today)->count();
        $pendingReservations = Reservation::where('status', 'open')->count();
        $unreadContacts = Contact::whereNull('read_at')->count();

        $lunchBooked = $service->getBookedCount($today, 'lunch');
        $dinnerBooked = $service->getBookedCount($today, 'dinner');

        $upcomingReservations = Reservation::whereDate('date', '>=', $today)
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
            'sessionStats' => [
                'lunch' => [
                    'booked' => $lunchBooked,
                    'max' => ReservationService::MAX_CAPACITY,
                ],
                'dinner' => [
                    'booked' => $dinnerBooked,
                    'max' => ReservationService::MAX_CAPACITY,
                ],
            ],
            'upcoming_reservations' => $upcomingReservations,
        ]);
    }
}
