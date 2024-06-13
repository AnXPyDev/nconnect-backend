<?php

namespace Database\Seeders;

use App\Models\Presentation;
use App\Models\Speaker;
use App\Models\Gallery;
use App\Models\Resource;
use App\Models\Stage;
use App\Models\Testimonial;
use App\Models\Timeslot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;
use File;

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

        $stages_data = json_decode(File::get("database/data/stages.json"), true);

        $stages = Stage::factory()->createMany($stages_data);

        $events_data = json_decode(File::get("database/data/events.json"), true);
        $events = Presentation::factory()->createMany($events_data);

        $timeslots_data = json_decode(File::get("database/data/timeslots.json"), true);
        foreach ($stages as $stage) {
            $timeslots = $stage->timeslots()->createMany($timeslots_data);
            $timeslots[0]->presentation_id = $events[0]->id;
            $timeslots[0]->save();
            $timeslots[1]->presentation_id = $events[1]->id;
            $timeslots[1]->save();
            $timeslots[5]->presentation_id = $events[2]->id;
            $timeslots[5]->save();
            $timeslots[8]->presentation_id = $events[3]->id;
            $timeslots[8]->save();
        }

        $speakers_gallery = Gallery::factory()->create([
            'name' => 'speakers'
        ]);

        $speakers_data = json_decode(File::get("database/data/speakers.json"), true);
        foreach ($speakers_data as $speaker_data) {
            $image_path = $speaker_data['image'];
            unset($speaker_data['image']);
            $speaker = Speaker::factory()->create($speaker_data);
            $image = Resource::factory()->create([
                'name' => $speaker_data['name'],
                'path' => $image_path,
                'type' => 'image'
            ]);
            $speakers_gallery->addImage($image);

            $speaker->image_id = $image->id;
            $speaker->save();
        }
    }
}
