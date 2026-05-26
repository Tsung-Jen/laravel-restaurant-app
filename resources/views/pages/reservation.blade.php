@extends('layouts.app')

@section('title', __('messages.reservation'))

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12 sm:py-16">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-stone-800">@lang('messages.reservation')</h1>
        <p class="text-stone-500 mt-2">@lang('messages.reservation_subtitle')</p>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl mb-8 flex items-center gap-3 shadow-sm">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('large_group'))
        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-5 py-4 rounded-xl mb-8 flex items-center gap-3 shadow-sm">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('large_group') }}</span>
        </div>
    @endif

    @if ($isLunchFull || $isDinnerFull)
        <div class="space-y-3 mb-6">
            @if ($isLunchFull)
                <div class="bg-amber-50 border border-amber-200 text-amber-700 px-5 py-3 rounded-xl text-sm flex items-center gap-3 shadow-sm">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>@lang('messages.session_lunch'): @lang('messages.session_booked_out')</span>
                </div>
            @endif
            @if ($isDinnerFull)
                <div class="bg-amber-50 border border-amber-200 text-amber-700 px-5 py-3 rounded-xl text-sm flex items-center gap-3 shadow-sm">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>@lang('messages.session_dinner'): @lang('messages.session_booked_out')</span>
                </div>
            @endif
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-lg shadow-stone-200/60 border border-stone-100 p-6 sm:p-8">
        <form method="POST" action="{{ route('reservation.store') }}" class="space-y-5" x-data="{ submitting: false, guests: {{ old('guests', 2) }} }" @submit="submitting = true">
            @csrf
            <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">

            <div>
                <label class="block text-sm font-semibold text-stone-700 mb-1.5">@lang('messages.name') <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full border-2 border-stone-200 rounded-xl px-4 py-3 text-stone-800 placeholder-stone-400 transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100 outline-none @error('name') border-red-300 focus:border-red-400 focus:ring-red-100 @enderror"
                    placeholder="@lang('messages.name_placeholder')">
                @error('name') <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1"><svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p> @enderror
            </div>

            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-stone-700 mb-1.5">@lang('messages.email')</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full border-2 border-stone-200 rounded-xl px-4 py-3 text-stone-800 placeholder-stone-400 transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100 outline-none @error('email') border-red-300 focus:border-red-400 focus:ring-red-100 @enderror"
                        placeholder="@lang('messages.email_placeholder')">
                    <p class="text-xs text-stone-400 mt-1">@lang('messages.email_optional_hint')</p>
                    @error('email') <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1"><svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-stone-700 mb-1.5">@lang('messages.phone') <span class="text-red-400">*</span></label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required
                        class="w-full border-2 border-stone-200 rounded-xl px-4 py-3 text-stone-800 placeholder-stone-400 transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100 outline-none @error('phone') border-red-300 focus:border-red-400 focus:ring-red-100 @enderror"
                        placeholder="@lang('messages.phone_placeholder')">
                    @error('phone') <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1"><svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-stone-700 mb-1.5">@lang('messages.date') <span class="text-red-400">*</span></label>
                    <input type="date" name="date" value="{{ old('date') }}" required min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('+2 months')) }}"
                        class="w-full border-2 border-stone-200 rounded-xl px-4 py-3 text-stone-800 transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100 outline-none @error('date') border-red-300 focus:border-red-400 focus:ring-red-100 @enderror">
                    @error('date') <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1"><svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-stone-700 mb-1.5">@lang('messages.time') <span class="text-red-400">*</span></label>
                    <input type="time" name="time" value="{{ old('time') }}" required
                        class="w-full border-2 border-stone-200 rounded-xl px-4 py-3 text-stone-800 transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100 outline-none @error('time') border-red-300 focus:border-red-400 focus:ring-red-100 @enderror">
                    @error('time') <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1"><svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-stone-700 mb-1.5">@lang('messages.guests') <span class="text-red-400">*</span></label>
                    <input type="number" name="guests" x-model.number="guests" value="{{ old('guests', 2) }}" required min="1" max="99"
                        class="w-full border-2 border-stone-200 rounded-xl px-4 py-3 text-stone-800 transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100 outline-none @error('guests') border-red-300 focus:border-red-400 focus:ring-red-100 @enderror"
                        placeholder="2">
                    @error('guests') <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1"><svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p> @enderror
                </div>
            </div>

            <div x-show="guests >= 15" x-transition
                 class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl text-sm flex items-center gap-3">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>@lang('messages.large_group_notice')</span>
            </div>

            <div>
                <label class="block text-sm font-semibold text-stone-700 mb-1.5">@lang('messages.notes')</label>
                <textarea name="notes" rows="3" placeholder="@lang('messages.notes_placeholder')"
                    class="w-full border-2 border-stone-200 rounded-xl px-4 py-3 text-stone-800 placeholder-stone-400 transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100 outline-none @error('notes') border-red-300 focus:border-red-400 focus:ring-red-100 @enderror">{{ old('notes') }}</textarea>
                @error('notes') <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1"><svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p> @enderror
            </div>

            <button type="submit" :disabled="submitting"
                class="w-full bg-amber-600 hover:bg-amber-500 active:bg-amber-700 text-white font-semibold py-3.5 rounded-xl transition shadow-lg shadow-amber-200/50 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                <svg x-show="!submitting" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <svg x-show="submitting" class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                <span x-text="submitting ? '@lang('messages.sending')' : '@lang('messages.send_reservation')'"></span>
            </button>
        </form>
    </div>
</div>
@endsection
