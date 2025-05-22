<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;
use App\Enums\ModuleType;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        foreach(ModuleType::cases() as $module) {
            Module::create([
                'name' => $module->value,
            ]);
        }
    }
}
