<?php

namespace App\Http\Controllers;

use App\Http\Codes;
use App\Models\Presentation;
use App\Models\Speaker;
use App\Models\Stage;
use App\Models\Timeslot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PresentationController extends Controller
{

    function create() {
        $req = $this->validate([
            'name' => 'required|string',
            'description' => 'string|nullable',
            'long_description' => 'string|nullable',
            'speaker_id' => 'nullable|exists:speakers,id',
            'image_id' => 'nullable|exists:resources,id',
        ]);

        $presentation = Presentation::factory()->make([
            'name' => $req['name'],
            'description' => $req['description'],
            'long_description' => $req['long_description'],
            'speaker_id' => $req['speaker_id'] ?? null,
            'image_id' => $req['image_id'] ?? null
        ]);

        $presentation->save();

        return response()->json(['presentation' => $presentation]);
    }

    function edit() {
        $req = $this->validate([
            'id' => 'required|exists:presentations,id',
            'name' => 'required|string',
            'description' => 'string|nullable',
            'long_description' => 'string|nullable',
            'image_id' => 'nullable|exists:resources,id'
        ]);

        $presentation = Presentation::find($req["id"]);

        $presentation->name = $req["name"];
        $presentation->description = $req["description"];
        $presentation->long_description = $req["long_description"];
        $presentation->image_id = $req["image_id"] ?? null;

        $presentation->save();

        return response()->json(['presentation' => $presentation]);
    }

    function available() {
        $req = $this->validate([
            'timeslot_id' => 'nullable|exists:timeslots,id',
        ]);

        $available = [];

        foreach (Presentation::all() as $presentation) {
            if (!$presentation->timeslot()->exists()) {
                $available[] = $presentation;
            }
        }

        if (array_key_exists('timeslot_id', $req)) {
            $timeslot = Timeslot::find($req['timeslot_id']);
            $presentation = $timeslot->presentation();
            if ($presentation->exists()) {
                $available[] = $presentation->first();
            }
        }

        return response()->json([
            'presentations' => $available
        ]);
    }

    function delete() {
        $req = $this->validate([
            'id' => 'required|exists:presentations,id'
        ]);


        $presentation = Presentation::find($req['id']);
        $has_timeslot = $presentation->timeslot();

        if ($has_timeslot->exists()) {
            $timeslot = $has_timeslot->first();
            $timeslot->presentation_id = null;
            $timeslot->save();
        }

        return response()->json([]);
    }

    function speaker() {
        $req = $this->validate([
            'id' => 'required|exists:presentations,id'
        ]);

        $presentation = Presentation::find($req['id']);

        return response()->json([
            'speaker' => $presentation->speaker()
        ]);
    }

}
