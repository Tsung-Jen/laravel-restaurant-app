<?php

namespace App\Reservations\Controllers;

use App\Reservations\Mail\ReservationConfirmation;
use App\Reservations\Models\Reservation;
use App\Reservations\Requests\StoreReservationRequest;
use App\Reservations\Resources\ReservationResource;
use App\Reservations\Services\ChallengeService;
use App\Reservations\Services\ReservationService;
use Illuminate\Support\Facades\Mail;

class PublicReservationController extends Controller
{
    public function welcome()
    {
        return view('pages.welcome');
    }

    public function create()
    {
        $service = app(ReservationService::class);
        $today = now()->format('Y-m-d');

        return view('pages.reservation', [
            'isLunchFull' => $service->isSessionFull($today, 'lunch'),
            'isDinnerFull' => $service->isSessionFull($today, 'dinner'),
            'challenge' => app(ChallengeService::class)->generate(),
        ]);
    }

    public function store(StoreReservationRequest $request)
    {
        $reservation = Reservation::create($request->safe()->except(['cf-turnstile-response', 'form_loaded_at', 'challenge_token', 'challenge_answer']));

        if ($reservation->email) {
            Mail::to($reservation->email)->send(
                new ReservationConfirmation($reservation)
            );
        }

        if ($request->wantsJson()) {
            return ReservationResource::make($reservation)
                ->response()
                ->setStatusCode(201);
        }

        if ($reservation->guests >= 15) {
            return redirect()
                ->route('reservation.create')
                ->with('success', __('messages.reservation_success'))
                ->with('large_group', __('messages.large_group_notice'));
        }

        return redirect()
            ->route('reservation.create')
            ->with('success', __('messages.reservation_success'));
    }
}
