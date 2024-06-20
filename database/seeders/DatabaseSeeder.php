<?php

namespace Database\Seeders;

use App\Models\Conference;
use App\Models\Presentation;
use App\Models\Speaker;
use App\Models\Gallery;
use App\Models\Resource;
use App\Models\Sponsor;
use App\Models\Stage;
use App\Models\Testimonial;
use App\Models\Organizer;
use App\Models\Qna;
use App\Models\Timeslot;
use App\Models\Headliner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;
use File;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    function makeImage($path) {
        return Resource::factory()->create([
            'name' => pathinfo($path, PATHINFO_FILENAME),
            'path' => $path,
            'type' => 'image'
        ]);
    }

    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
        ]);

        $faker = fake();

        $conference_data = json_decode(File::get("database/data/conference.json"), true);
        Conference::factory()->create($conference_data);

        $events_data = json_decode(File::get("database/data/events.json"), true);

        $events = [];

        foreach ($events_data as $event_data) {
            $sid = $event_data['sid'];
            unset($event_data['sid']);
            $events[$sid] = Presentation::factory()->create($event_data);
        }


        $timeslots_data = json_decode(File::get("database/data/timeslots.json"), true);
        $stages_data = json_decode(File::get("database/data/stages.json"), true);

        $stages = [];

        foreach ($stages_data as $stage_data) {
            $sid = $stage_data['sid'];
            unset($stage_data['sid']);
            $stage = Stage::factory()->create($stage_data);
            $stages[$sid] = $stage;

            foreach ($timeslots_data as $timeslot_data) {
                $event_sid = $timeslot_data['event_sid'] ?? null;
                unset($timeslot_data['event_sid']);

                $timeslot = $stage->timeslots()->create($timeslot_data);

                if (!is_null($event_sid)) {
                    $timeslot->presentation_id = $events[$event_sid]->id;
                    $timeslot->save();
                }
            }
        }

        $speakers_gallery = Gallery::factory()->create([
            'name' => 'Speakers'
        ]);

        $speakers_data = json_decode(File::get("database/data/speakers.json"), true);
        foreach ($speakers_data as $speaker_data) {
            $image_path = $speaker_data['image'];
            $presentations_data = $speaker_data['presentations'] ?? [];
            $headliner_stage_sid = $speaker_data['headliner_stage_sid'] ?? null;
            unset($speaker_data['presentations'], $speaker_data['image'], $speaker_data['headliner_stage_sid']);

            $speaker = Speaker::factory()->create($speaker_data);
            $image = $this->makeImage($image_path);
            $speakers_gallery->addImage($image);

            $speaker->image_id = $image->id;
            $speaker->save();

            foreach ($presentations_data as $presentation_data) {
                $stage_sid = $presentation_data['stage_sid'];
                $timeslot_index = $presentation_data['timeslot_index'];
                unset($presentation_data['stage_sid'], $presentation_data['timeslot_index']);

                $presentation = $speaker->presentations()->create($presentation_data);

                $timeslot = $stages[$stage_sid]->timeslots()->get()[$timeslot_index];
                $timeslot->presentation_id = $presentation->id;
                $timeslot->save();
            }

            if (!is_null($headliner_stage_sid)) {
                Headliner::factory()->create([
                    'stage_id' => $stages[$headliner_stage_sid]->id,
                    'speaker_id' => $speaker->id
                ]);
            }
        }

        $testimonials_gallery = Gallery::factory()->create([
            'name' => 'Testimonials'
        ]);

        $testimonials_data = json_decode(File::get("database/data/testimonials.json"), true);
        foreach ($testimonials_data as $testimonial_data) {
            $image_path = $testimonial_data['image'];
            unset($testimonial_data['image']);

            $testimonial = testimonial::factory()->create($testimonial_data);
            $image = $this->makeImage($image_path);
            $testimonials_gallery->addImage($image);

            $testimonial->image_id = $image->id;
            $testimonial->save();
        }

        $sponsors_gallery = Gallery::factory()->create([
            'name' => 'Sponsors'
        ]);

        $sponsors_data = json_decode(File::get("database/data/sponsors.json"), true);
        foreach ($sponsors_data as $sponsor_data) {
            $image_path = $sponsor_data['image'];
            unset($sponsor_data['image']);

            $sponsor = Sponsor::factory()->create($sponsor_data);
            $image = $this->makeImage($image_path);
            $sponsors_gallery->addImage($image);

            $sponsor->image_id = $image->id;
            $sponsor->save();
        }

        $organizers_gallery = Gallery::factory()->create([
            'name' => 'Organizers'
        ]);

        $organizers_data = json_decode(File::get("database/data/organizers.json"), true);
        foreach ($organizers_data as $organizer_data) {
            $image_path = $organizer_data['image'];
            unset($organizer_data['image']);

            $organizer = Organizer::factory()->create($organizer_data);
            $image = $this->makeImage($image_path);
            $organizers_gallery->addImage($image);

            $organizer->image_id = $image->id;
            $organizer->save();
        }

        $qnas_data = json_decode(File::get("database/data/qnas.json"), true);
        Qna::factory()->createMany($qnas_data);
    }
}
