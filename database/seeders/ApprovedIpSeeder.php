<?php

namespace Database\Seeders;

use App\Models\ApprovedIp;
use Illuminate\Database\Seeder;
use App\Enums\ModuleType;

class ApprovedIpSeeder extends Seeder
{
    public function run(): void
    {
        $serverIp = gethostbyname(parse_url(env('APP_URL'), PHP_URL_HOST));

        if (!ApprovedIp::where('address', $serverIp)->exists()) {
            ApprovedIp::create([
                'address' => $serverIp,
            ]);
        }
    }
}
