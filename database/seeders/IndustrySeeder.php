<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Seeder;
use App\Models\Industry;
use App\Models\ContentBlock;

class IndustrySeeder extends Seeder
{
    public function run(): void
    {
        $data = json_decode(Storage::disk('storage')->get('content.json'), true)['industries'];

        foreach ($data as $indusryData) {
            $industry = Industry::create([
                'name'    => $indusryData['name'],
                'active' => $indusryData['active'] ?? false
            ]);

            generateContentBlocks($industry, $indusryData['contentBlocks'] ?? null);
        }
    }
}
