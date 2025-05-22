<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\TeamMember;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = json_decode(Storage::disk('storage')->get('content.json'), true)['departments'];

        foreach ($departments as $department) {
            $department = Department::create([
                'name' => $department,
            ]);
        }
    }
}
