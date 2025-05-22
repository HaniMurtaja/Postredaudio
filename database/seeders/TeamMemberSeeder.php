<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Seeder;
use App\Models\TeamMember;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        Storage::deleteDirectory('public/teammember');
        Storage::makeDirectory('public/teammember');

        $data = json_decode(Storage::disk('storage')->get('content.json'), true)['team_members'];

        foreach ($data as $teamMemberData) {
            $teamMember = TeamMember::create([
                'name' => $teamMemberData['name'],
                'position' => $teamMemberData['position'],
                'bio' => $teamMemberData['bio'],
                'links' => createFlexibleField($teamMemberData['links'], 'link_item'),
            ]);

            $teamMember->departments()->attach(Department::whereIn('name', $teamMemberData['departments'])->get());

            $teamMemberImage = imageExists($teamMemberData['image']) ? $teamMemberData['image'] : 'images/members/team-generic.jpg';
            $teamMember->addMedia(storage_path($teamMemberImage))
                ->preservingOriginal()
                ->toMediaCollection('photo');
        }
    }
}
