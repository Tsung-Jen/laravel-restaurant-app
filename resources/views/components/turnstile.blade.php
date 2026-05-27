@props(['challenge' => null, 'error' => null])

<div
    x-data="{
        turnstileAvailable: typeof turnstile !== 'undefined',
        token: '',
        challengeAnswer: '{{ old('challenge_answer') }}',
        challengeToken: '{{ $challenge['token'] ?? '' }}',
        challengeQuestion: '{{ $challenge['question'] ?? '' }}',
    }"
    x-init="
        if (turnstileAvailable) {
            turnstile.render($el.querySelector('.cf-turnstile'), {
                sitekey: '{{ config('services.turnstile.site_key') }}',
                callback: (t) => { token = t; },
            });
        }
    "
>
    <div x-show="turnstileAvailable" class="cf-turnstile"></div>
    <input type="hidden" name="cf-turnstile-response" x-model="token" />

    <div x-show="!token" class="space-y-2">
        <input type="hidden" name="challenge_token" x-model="challengeToken" />
        <label class="block text-sm font-semibold text-stone-700 mb-1.5">@lang('messages.math_challenge')</label>
        <div class="flex items-center gap-3">
            <span class="text-lg font-medium text-stone-800" x-text="challengeQuestion"></span>
            <span class="text-stone-400">=</span>
            <input type="number" name="challenge_answer" x-model.number="challengeAnswer" required
                class="w-24 border-2 border-stone-200 rounded-xl px-4 py-3 text-stone-800 transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100 outline-none @error('challenge_answer') border-red-300 @enderror"
                placeholder="?">
        </div>
    </div>
</div>

@if ($error)
    <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1">
        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ $error }}
    </p>
@endif

<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
