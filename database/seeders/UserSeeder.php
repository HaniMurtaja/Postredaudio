<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'admin@postred.com')->first();

        if (!$user) {
            User::factory()->create([
                'name'      => 'Admin',
                'email'     => 'admin@postred.com',
                'password'  => Hash::make('pass@postred3'),
                'admin'  => true,
            ]);
        }
    }
}
