<?php

namespace App\OpeningHours\Models;

use Illuminate\Database\Eloquent\Model;

class OpeningHour extends Model
{
    protected $fillable = [
        'day_of_week',
        'lunch_start',
        'lunch_end',
        'evening_start',
        'evening_end',
        'is_closed',
        'closed_except_week',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'is_closed' => 'boolean',
        'closed_except_week' => 'string',
    ];
}
