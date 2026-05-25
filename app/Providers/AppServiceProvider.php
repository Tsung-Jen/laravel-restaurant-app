<?php

namespace App\Providers;

use App\Contact\Models\Contact;
use App\OpeningHours\Models\OpeningHour;
use App\OpeningHours\Models\Holiday;
use App\OpeningHours\Models\Vacation;
use App\Reservations\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Route::model('reservation', Reservation::class);
        Route::model('contact', Contact::class);
        Route::model('holiday', Holiday::class);

        View::composer('layouts.app', function ($view) {
            $hours = OpeningHour::orderBy('day_of_week')->get()->keyBy('day_of_week');
            $holidays = Holiday::whereYear('date', Carbon::now()->year)->pluck('date')->map(fn ($d) => $d->format('Y-m-d'))->toArray();
            $currentWeek = Carbon::now()->format('o-\WW');
            $today = Carbon::now()->format('Y-m-d');

            // For the banner: all future/current vacations (ending today or later)
            $futureVacations = Vacation::where('end_date', '>=', $today)
                ->orderBy('start_date')->get();

            $onVacationNow = $futureVacations->contains(function ($v) use ($today) {
                return $v->start_date->format('Y-m-d') <= $today && $v->end_date->format('Y-m-d') >= $today;
            });

            // For per-day footer check: Mon–Sun week
            // $dow mapping: 0=Mon, 1=Tue, 2=Wed, 3=Thu, 4=Fri, 5=Sat, 6=Sun
            // PHP day_of_week: 0=Sun, 1=Mon, 2=Tue, 3=Wed, 4=Thu, 5=Fri, 6=Sat
            $todayDate = Carbon::now();
            $todayDow = (int) $todayDate->format('w');
            $daysToMonday = $todayDow === 0 ? -6 : 1 - $todayDow;
            $monday = (clone $todayDate)->addDays($daysToMonday);
            $sunday = (clone $monday)->addDays(6);
            $weekVacations = Vacation::where('start_date', '<=', $sunday->format('Y-m-d'))
                ->where('end_date', '>=', $monday->format('Y-m-d'))
                ->get();

            $footerDayOnVacation = [];
            $footerDayDates = [];
            foreach (range(0, 6) as $i) {
                $actualDow = ($i + 1) % 7; // 0=Mon->1, 1=Tue->2, ..., 5=Sat->6, 6=Sun->0
                $date = (clone $monday)->addDays($i)->format('Y-m-d');
                $footerDayOnVacation[$i] = $weekVacations->contains(function ($v) use ($date) {
                    return $v->start_date->format('Y-m-d') <= $date && $v->end_date->format('Y-m-d') >= $date;
                });
                $footerDayDates[$i] = $date;
            }

            $view->with('footerHours', $hours)
                 ->with('footerHolidays', $holidays)
                 ->with('footerCurrentWeek', $currentWeek)
                 ->with('footerVacations', $futureVacations)
                 ->with('footerOnVacation', $onVacationNow)
                 ->with('footerDayOnVacation', $footerDayOnVacation)
                 ->with('footerDayDates', $footerDayDates);
        });
    }
}
