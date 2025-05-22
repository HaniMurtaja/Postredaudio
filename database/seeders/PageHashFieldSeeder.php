<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Page;

class PageHashFieldSeeder extends Seeder
{
    public function run(): void
    {
        Page::whereNull('hash')->chunkById(100, function ($pages) {
            foreach ($pages as $page) {
                $page->hash = Str::random(16);
                $page->save();
            }
        });
    }
}
