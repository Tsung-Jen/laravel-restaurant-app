<?php

namespace App\Reservations\Requests;

use App\OpeningHours\Services\OpeningHoursService;
use App\Reservations\Models\Reservation;
use App\Reservations\Services\ChallengeService;
use App\Reservations\Services\ReservationService;
use App\Rules\Turnstile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => 'nullable|email|max:255',
            'phone' => ['required', 'string', 'max:20', 'regex:/^(\+49|0)[\d\s\-\/\(\)]{6,18}$/'],
            'guests' => 'required|integer|min:1|max:99',
            'date' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:+2 months'],
            'time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
            'website' => 'prohibited',
            'form_loaded_at' => 'required|integer',
            'cf-turnstile-response' => ['nullable', new Turnstile],
            'challenge_token' => 'nullable|string',
            'challenge_answer' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'date.after_or_equal' => __('validation.date_after_today'),
            'date.before_or_equal' => __('validation.advance_date'),
            'guests.min' => __('validation.guests_min'),
            'guests.max' => __('validation.guests_max'),
            'name.min' => __('validation.name_min'),
            'website.prohibited' => __('validation.spam_detected'),
            'phone.regex' => __('validation.phone_german'),
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $formLoadedAt = (int) $this->input('form_loaded_at');
            if ($formLoadedAt > 0 && (now()->valueOf() - $formLoadedAt) < 5000) {
                $validator->errors()->add('form_loaded_at', __('validation.submitted_too_fast'));

                return;
            }

            $turnstileResponse = $this->input('cf-turnstile-response');
            if (! $turnstileResponse) {
                $challengeToken = $this->input('challenge_token');
                $challengeAnswer = $this->input('challenge_answer');

                if (! $challengeToken || ! $challengeAnswer) {
                    $validator->errors()->add('challenge_answer', __('validation.challenge_required'));

                    return;
                }

                if (! app(ChallengeService::class)->verify($challengeToken, (int) $challengeAnswer)) {
                    $validator->errors()->add('challenge_answer', __('validation.challenge_wrong'));

                    return;
                }
            }

            $date = $this->input('date');
            $time = $this->input('time');
            $guests = (int) $this->input('guests');
            $email = $this->input('email');
            $phone = $this->input('phone');

            if (! $date || ! $time) {
                return;
            }

            // Check opening hours
            $openingHours = app(OpeningHoursService::class);
            if (! $openingHours->isOpen($date, $time)) {
                $validator->errors()->add('time', __('validation.opening_hours'));

                return;
            }

            // Determine session (lunch/dinner)
            $reservationService = app(ReservationService::class);
            $session = $reservationService->getSession($time);

            if (! $session) {
                $validator->errors()->add('time', __('validation.opening_hours'));

                return;
            }

            // Advance time check (today only)
            if ($date === now()->format('Y-m-d')) {
                $bufferMinutes = 30;
                $timeMinutes = $this->timeToMinutes($time);
                $nowMinutes = now()->hour * 60 + now()->minute + $bufferMinutes;
                if ($timeMinutes <= $nowMinutes) {
                    $validator->errors()->add('time', __('validation.advance_time'));

                    return;
                }
            }

            // Capacity check (with pessimistic lock to prevent race condition)
            DB::transaction(function () use ($validator, $date, $session, $guests, $email, $phone) {
                $existingSum = Reservation::whereDate('date', $date)
                    ->where('status', '!=', 'cancelled')
                    ->whereRaw(
                        "CASE WHEN time >= '11:30' AND time < '14:30' THEN 'lunch' WHEN time >= '17:30' AND time < '23:00' THEN 'dinner' END = ?",
                        [$session]
                    )
                    ->lockForUpdate()
                    ->sum('guests');

                if ($existingSum + $guests > ReservationService::MAX_CAPACITY) {
                    $validator->errors()->add('guests', __('validation.session_full'));

                    return;
                }

                // Duplicate window check (same email and session within 60 min)
                if ($email || $phone) {
                    $recent = Reservation::whereDate('date', $date)
                        ->where('created_at', '>=', now()->subHour())
                        ->where(function ($q) use ($email, $phone) {
                            if ($email) {
                                $q->orWhere('email', $email);
                            }
                            if ($phone) {
                                $q->orWhere('phone', $phone);
                            }
                        })
                        ->whereRaw(
                            "CASE WHEN time >= '11:30' AND time < '14:30' THEN 'lunch' WHEN time >= '17:30' AND time < '23:00' THEN 'dinner' END = ?",
                            [$session]
                        )
                        ->lockForUpdate()
                        ->exists();

                    if ($recent) {
                        $validator->errors()->add('email', __('validation.duplicate_reservation'));
                    }
                }
            });
        });
    }

    private function timeToMinutes(string $time): int
    {
        [$h, $m] = explode(':', $time);

        return (int) $h * 60 + (int) $m;
    }
}
