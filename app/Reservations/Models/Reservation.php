<?php

namespace App\Reservations\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'guests',
        'date',
        'time',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
            'time' => 'string',
            'guests' => 'integer',
        ];
    }
}
