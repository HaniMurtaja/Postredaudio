<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Industry;


class ClientSeeder extends Seeder
{
    public function run(): void
    {
        Storage::deleteDirectory('public/client');
        Storage::makeDirectory('public/client');

        $data = json_decode(Storage::disk('storage')->get('content.json'), true)['clients'];

        foreach ($data as $index => $clientData) {
            $client = Client::create([
                "name" => $clientData["name"],
                "industry_id" => $clientData["industry"] ? Industry::where("name", $clientData["industry"])->pluck("id")->first() : null
            ]);

            $clientImage = imageExists($clientData['logo'] ?? null) ? $clientData['logo'] : "images/clients/default-logo.svg";
            $client->addMedia(storage_path($clientImage))
                ->preservingOriginal()
                ->toMediaCollection('logo');
        }
    }
}
