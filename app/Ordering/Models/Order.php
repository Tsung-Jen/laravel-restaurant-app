<?php

namespace App\Ordering\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'pickup_date',
        'pickup_time',
        'phone',
        'payment_method',
        'subtotal',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'pickup_date' => 'date:Y-m-d',
            'subtotal' => 'decimal:2',
        ];
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
