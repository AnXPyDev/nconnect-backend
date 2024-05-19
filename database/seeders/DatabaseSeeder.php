<?php

namespace Database\Seeders;

use App\Models\Speaker;
use App\Models\Stage;
use App\Models\Timeslot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
        ]);

        $faker = fake();

        $stage_softdev = Stage::factory()->create([
            'name' => "SOFT DEV STAGE"
        ]);

        $stage_aidata = Stage::factory()->create([
            'name' => "AI&DATA STAGE"
        ]);

        foreach ([$stage_aidata, $stage_softdev] as $stage) {
            Timeslot::factory()->createMany([
                [
                    'stage_id' => $stage->id,
                    'start_at' => '2020-01-01 12:00:00',
                    'end_at' => '2020-01-01 13:30:00'
                ],
                [
                    'stage_id' => $stage->id,
                    'start_at' => '2020-01-01 14:00:00',
                    'end_at' => '2020-01-01 15:30:00'
                ],
                [
                    'stage_id' => $stage->id,
                    'start_at' => '2020-01-01 16:00:00',
                    'end_at' => '2020-01-01 17:30:00'
                ]
            ]);
        }

        for ($i = 1; $i <= 5; $i++) {
            Speaker::factory()->create([
                "name" => $faker->name(),
                "description" => $faker->text()
            ]);
        }

    }
}
