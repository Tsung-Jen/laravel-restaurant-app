<?php

namespace App\Ordering\Requests;

use App\OpeningHours\Services\OpeningHoursService;
use App\Ordering\Services\CartService;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pickup_date' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:+2 weeks'],
            'pickup_time' => 'required|date_format:H:i',
            'phone' => ['required', 'string', 'max:20', 'regex:/^(\+49|0)[\d\s\-\/\(\)]{6,18}$/'],
            'payment_method' => 'required|in:cash,card',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => __('validation.phone_german'),
            'payment_method.in' => __('validation.payment_method_invalid'),
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (! app(CartService::class)->isEmpty()) {
                return;
            }

            $validator->errors()->add('cart', __('validation.cart_empty'));
        });

        $validator->after(function ($validator) {
            $date = $this->input('pickup_date');
            $time = $this->input('pickup_time');

            if (! $date || ! $time) {
                return;
            }

            $openingHours = app(OpeningHoursService::class);
            if (! $openingHours->isOpen($date, $time)) {
                $validator->errors()->add('pickup_time', __('validation.opening_hours'));
            }
        });
    }
}
