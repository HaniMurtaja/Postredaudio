<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Testimonial;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $data = json_decode(Storage::disk('storage')->get('content.json'), true)['services'];

        foreach ($data as $serviceData) {
            $service = Service::create([
                'name'    => $serviceData['name'],
                'active' => $serviceData['active'] ?? false
            ]);

            generateContentBlocks($service, $serviceData['contentBlocks'] ?? null);
        }
    }
}
