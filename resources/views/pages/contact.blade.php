@extends('layouts.app')

@section('title', __('messages.contact'))

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12 sm:py-16">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-stone-800">@lang('messages.contact')</h1>
        <p class="text-stone-500 mt-2">@lang('messages.contact_subtitle')</p>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl mb-8 flex items-center gap-3 shadow-sm">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-lg shadow-stone-200/60 border border-stone-100 p-6 sm:p-8">
        <form method="POST" action="{{ route('contact.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-stone-700 mb-1.5">@lang('messages.name') <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full border-2 border-stone-200 rounded-xl px-4 py-3 text-stone-800 placeholder-stone-400 transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100 outline-none @error('name') border-red-300 focus:border-red-400 focus:ring-red-100 @enderror"
                    placeholder="@lang('messages.name_placeholder')">
                @error('name') <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1"><svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-stone-700 mb-1.5">@lang('messages.email') <span class="text-red-400">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full border-2 border-stone-200 rounded-xl px-4 py-3 text-stone-800 placeholder-stone-400 transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100 outline-none @error('email') border-red-300 focus:border-red-400 focus:ring-red-100 @enderror"
                    placeholder="@lang('messages.email_placeholder')">
                @error('email') <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1"><svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-stone-700 mb-1.5">@lang('messages.subject') <span class="text-red-400">*</span></label>
                <input type="text" name="subject" value="{{ old('subject') }}" required
                    class="w-full border-2 border-stone-200 rounded-xl px-4 py-3 text-stone-800 placeholder-stone-400 transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100 outline-none @error('subject') border-red-300 focus:border-red-400 focus:ring-red-100 @enderror"
                    placeholder="@lang('messages.subject_placeholder')">
                @error('subject') <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1"><svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-stone-700 mb-1.5">@lang('messages.message') <span class="text-red-400">*</span></label>
                <textarea name="message" rows="5" required
                    class="w-full border-2 border-stone-200 rounded-xl px-4 py-3 text-stone-800 placeholder-stone-400 transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100 outline-none @error('message') border-red-300 focus:border-red-400 focus:ring-red-100 @enderror"
                    placeholder="@lang('messages.message_placeholder')">{{ old('message') }}</textarea>
                @error('message') <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1"><svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                class="w-full bg-amber-600 hover:bg-amber-500 active:bg-amber-700 text-white font-semibold py-3.5 rounded-xl transition shadow-lg shadow-amber-200/50 flex items-center justify-center gap-2">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                @lang('messages.send_message')
            </button>
        </form>
    </div>
</div>
@endsection
