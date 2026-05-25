<?php

namespace App\Reservations\Controllers;

use App\Reservations\Models\Reservation;
use App\Reservations\Requests\StoreReservationRequest;
use App\Reservations\Resources\ReservationResource;
use Illuminate\Http\Request;

class PublicReservationController extends Controller
{
    public function welcome()
    {
        return view('pages.welcome');
    }

    public function create()
    {
        return view('pages.reservation');
    }

    public function store(StoreReservationRequest $request)
    {
        $reservation = Reservation::create($request->validated());

        if ($request->wantsJson()) {
            return ReservationResource::make($reservation)
                ->response()
                ->setStatusCode(201);
        }

        return redirect()
            ->route('reservation.create')
            ->with('success', __('messages.reservation_success'));
    }
}
