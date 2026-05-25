<?php

namespace App\Reservations\Requests;

use App\OpeningHours\Services\OpeningHoursService;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => ['required', 'string', 'max:20', 'regex:/^(\+49|0)[\d\s\-\/\(\)]{6,18}$/'],
            'guests'  => 'required|integer|min:1|max:99',
            'date'    => 'required|date|after_or_equal:today',
            'time'    => 'required|date_format:H:i',
            'notes'   => 'nullable|string|max:1000',
            'website' => 'prohibited',
        ];
    }

    public function messages(): array
    {
        return [
            'date.after_or_equal' => __('validation.date_after_today'),
            'guests.min'          => __('validation.guests_min'),
            'guests.max'          => __('validation.guests_max'),
            'website.prohibited'  => __('validation.spam_detected'),
            'phone.regex'         => __('validation.phone_german'),
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $date = $this->input('date');
            $time = $this->input('time');

            if (! $date || ! $time) {
                return;
            }

            $service = app(OpeningHoursService::class);

            if (! $service->isOpen($date, $time)) {
                $validator->errors()->add('time', __('validation.opening_hours'));
            }
        });
    }
}
