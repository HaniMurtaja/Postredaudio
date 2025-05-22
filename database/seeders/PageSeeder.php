<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Storage;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $data = json_decode(Storage::disk('storage')->get('content.json'), true)['pages'];

        foreach ($data as $pageData) {
            $page = Page::create(['name' => $pageData['name']]);

            generateContentBlocks($page, $pageData['contentBlocks'] ?? null);
        }
    }
}
