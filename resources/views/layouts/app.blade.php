<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/public.js'])
    @include('components.cookie-consent')
</head>
<body class="font-sans antialiased bg-stone-50 text-stone-900">
    <nav class="bg-white shadow-sm border-b border-stone-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('home') }}" class="text-xl font-bold text-amber-800">
                    {{ config('app.name') }}
                </a>
                <div class="flex items-center gap-6 text-sm">
                    <a href="{{ route('home') }}" class="hover:text-amber-700 transition">@lang('messages.home')</a>
                    <a href="{{ route('menu.show') }}" class="hover:text-amber-700 transition">@lang('messages.menu')</a>
                    <a href="{{ route('reservation.create') }}" class="hover:text-amber-700 transition">@lang('messages.reservation')</a>
                    <a href="{{ route('contact.create') }}" class="hover:text-amber-700 transition">@lang('messages.contact')</a>
                    <div class="flex gap-2 ml-4 pl-4 border-l border-stone-200">
                        <a href="{{ route('locale.switch', 'de') }}" class="text-xs uppercase hover:text-amber-700 {{ app()->getLocale() === 'de' ? 'font-bold text-amber-800' : '' }}">De</a>
                        <a href="{{ route('locale.switch', 'en') }}" class="text-xs uppercase hover:text-amber-700 {{ app()->getLocale() === 'en' ? 'font-bold text-amber-800' : '' }}">En</a>
                        <a href="{{ route('locale.switch', 'zh_CN') }}" class="text-xs uppercase hover:text-amber-700 {{ app()->getLocale() === 'zh_CN' ? 'font-bold text-amber-800' : '' }}">Zh</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-stone-900 text-stone-400 pt-12 pb-8 mt-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($footerOnVacation)
                @php $todayStr = now()->format('Y-m-d'); @endphp
                @foreach ($footerVacations as $v)
                    @if ($v->start_date->format('Y-m-d') <= $todayStr && $v->end_date->format('Y-m-d') >= $todayStr)
                        <div class="bg-amber-900/30 border border-amber-700/40 text-amber-300 px-4 py-3 rounded-lg mb-8 text-sm text-center">
                            {{ __('messages.vacation_notice', ['from' => $v->start_date->format('d.m.Y'), 'to' => $v->end_date->format('d.m.Y')]) }}
                            @if ($v->reason)
                                <br><span class="text-amber-400 font-medium">{{ $v->reason }}</span>
                            @endif
                        </div>
                    @endif
                @endforeach
            @endif

            <div class="grid sm:grid-cols-2 gap-8 pb-8 border-b border-stone-700">
                <div>
                    <h3 class="text-amber-400 font-semibold text-lg mb-2">{{ config('app.name') }}</h3>
                    <p class="text-sm leading-relaxed">
                        @lang('messages.welcome_text')
                    </p>
                    <div class="mt-4 space-y-2 text-sm">
                        <div class="flex items-start gap-2">
                            <svg class="h-4 w-4 mt-0.5 shrink-0 text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>@lang('messages.address')</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg class="h-4 w-4 mt-0.5 shrink-0 text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <a href="tel:@lang('messages.footer_phone')" class="hover:text-amber-400 transition">@lang('messages.footer_phone')</a>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="text-amber-400 font-semibold text-lg mb-3">@lang('messages.opening_hours_title')</h3>
                    <table class="text-sm w-full">
                        @php $dayKeys = [0 => 'monday', 1 => 'tuesday', 2 => 'wednesday', 3 => 'thursday', 4 => 'friday', 5 => 'saturday', 6 => 'sunday']; @endphp
                        @php $hasHolidayException = false; $holidayExceptionLabel = ''; $restDayLabels = []; @endphp
                        @foreach ($dayKeys as $dow => $key)
                        @php
                            $actualDow = ($dow + 1) % 7; // 0=Mon->1, 1=Tue->2, ..., 5=Sat->6, 6=Sun->0
                            $h = $footerHours->get($actualDow);
                            $isHoliday = in_array($footerDayDates[$dow], $footerHolidays);
                            $isHolidayException = $isHoliday && $h && $h->is_closed;
                            if ($isHolidayException) { $hasHolidayException = true; $holidayExceptionLabel = __('messages.' . $key); }
                        @endphp
                        <tr class="{{ $loop->last && !$hasHolidayException && !count($restDayLabels) ? '' : 'border-b border-stone-800' }}">
                            <td class="py-1.5">@lang('messages.' . $key)</td>
                            <td class="py-1.5 text-right">
                                @if ($footerDayOnVacation[$dow] ?? false)
                                    <div>@lang('messages.closed_vacation')</div>
                                    @if ($h && $h->is_closed)
                                        @php $restDayLabels[] = __('messages.' . $key); @endphp
                                        <div class="text-xs text-stone-500 leading-tight">@lang('messages.closed')*</div>
                                    @elseif ($h && $h->lunch_start && $h->evening_start)
                                        <div class="text-xs text-stone-500 leading-tight">{{ substr($h->lunch_start, 0, 5) }}–{{ substr($h->lunch_end, 0, 5) }} &amp; {{ substr($h->evening_start, 0, 5) }}–{{ substr($h->evening_end, 0, 5) }}</div>
                                    @elseif ($h && $h->evening_start)
                                        <div class="text-xs text-stone-500 leading-tight">{{ substr($h->evening_start, 0, 5) }}–{{ substr($h->evening_end, 0, 5) }}</div>
                                    @else
                                        <div class="text-xs text-stone-500 leading-tight">@lang('messages.closed')</div>
                                    @endif
                                @elseif ($isHolidayException)
                                    @if ($h->lunch_start && $h->evening_start)
                                        {{ substr($h->lunch_start, 0, 5) }}–{{ substr($h->lunch_end, 0, 5) }} &amp; {{ substr($h->evening_start, 0, 5) }}–{{ substr($h->evening_end, 0, 5) }}*
                                    @elseif ($h->evening_start)
                                        {{ substr($h->evening_start, 0, 5) }}–{{ substr($h->evening_end, 0, 5) }}*
                                    @else
                                        <span class="text-stone-500">@lang('messages.closed')</span>
                                    @endif
                                @elseif ($h && $h->is_closed && $h->closed_except_week && $h->closed_except_week === $footerCurrentWeek)
                                    @if ($h->lunch_start && $h->evening_start)
                                        {{ substr($h->lunch_start, 0, 5) }}–{{ substr($h->lunch_end, 0, 5) }} &amp; {{ substr($h->evening_start, 0, 5) }}–{{ substr($h->evening_end, 0, 5) }}
                                    @elseif ($h->evening_start)
                                        {{ substr($h->evening_start, 0, 5) }}–{{ substr($h->evening_end, 0, 5) }}
                                    @else
                                        <span class="text-stone-500">@lang('messages.closed')</span>
                                    @endif
                                @elseif ($h && $h->is_closed)
                                    @php $restDayLabels[] = __('messages.' . $key); @endphp
                                    <span class="text-stone-500">@lang('messages.closed')*</span>
                                @elseif ($h && $h->lunch_start && $h->evening_start)
                                    {{ substr($h->lunch_start, 0, 5) }}–{{ substr($h->lunch_end, 0, 5) }} &amp; {{ substr($h->evening_start, 0, 5) }}–{{ substr($h->evening_end, 0, 5) }}
                                @elseif ($h && $h->evening_start)
                                    {{ substr($h->evening_start, 0, 5) }}–{{ substr($h->evening_end, 0, 5) }}
                                @else
                                    <span class="text-stone-500">@lang('messages.closed')</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @if ($hasHolidayException)
                            <tr>
                                <td colspan="2" class="pt-2 text-xs text-stone-400">
                                    * {{ __('messages.holiday_exception_note', ['day' => $holidayExceptionLabel]) }}
                                </td>
                            </tr>
                        @endif
                        @if (count($restDayLabels))
                            <tr>
                                <td colspan="2" class="pt-2 text-xs text-stone-400">
                                    * {{ __('messages.rest_day_note', ['day' => implode(', ', array_unique($restDayLabels))]) }}
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            <div class="py-8 border-b border-stone-700">
                <iframe
                    src="https://maps.google.com/maps?q=Markt+8,+08451+Crimmitschau&amp;output=embed"
                    width="100%"
                    height="280"
                    style="border:0; border-radius: 0.75rem;"
                    allowfullscreen
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    class="shadow-lg"
                ></iframe>
            </div>

            <div class="text-center text-sm pt-8">
                &copy; {{ date('Y') }} {{ config('app.name') }}. @lang('messages.all_rights')
            </div>
        </div>
    </footer>
</body>
</html>
