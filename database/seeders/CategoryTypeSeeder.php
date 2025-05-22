<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoryType;
use App\Enums\CategoryType as CategoryTypeEnum;

class CategoryTypeSeeder extends Seeder
{
    public function run(): void
    {
        foreach(CategoryTypeEnum::cases() as $type) {
            CategoryType::create([
                'name' => $type->value,
            ]);
        }
    }
}
