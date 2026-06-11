<?php

namespace Database\Seeders;

use App\Ordering\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Suppen und Vorspeise',
            'Maultaschen hausgemacht',
            'Für unsere kleinen Gäste',
            'Hühner Gerichte',
            'Enten Gerichte',
            'Reis und Nudeln',
            'Besonders zu empfehlen',
            'Spezialitäten auf heißer Platte',
            'Garnelen und Fische',
            'Vegetarische Gerichte',
            'Beilagen',
            'Nachtisch',
        ];

        foreach ($categories as $i => $name) {
            Category::firstOrCreate(
                ['name' => $name],
                ['sort_order' => $i + 1, 'is_active' => true],
            );
        }
    }
}
