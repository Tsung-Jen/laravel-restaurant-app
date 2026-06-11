<?php

namespace App\Ordering\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order');
    }

    public function activeMenuItems()
    {
        return $this->hasMany(MenuItem::class)->where('is_active', true)->orderBy('sort_order');
    }
}
