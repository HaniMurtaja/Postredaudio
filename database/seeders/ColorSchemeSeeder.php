<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ColorScheme;
use App\Enums\ColorScheme as ColorSchemeEnum;

class ColorSchemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(ColorSchemeEnum::cases() as $type) {
            ColorScheme::create([
                'name' => $type->value,
            ]);
        }
    }
}
