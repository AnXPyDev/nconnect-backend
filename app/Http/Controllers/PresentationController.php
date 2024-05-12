<?php

namespace App\Http\Controllers;

use App\Models\Presentation;
use App\Models\Speaker;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PresentationController extends Controller
{

    function create() {
        $req = request()->all();

        $stage = Stage::find($req['stage_id']);
        $speaker = Speaker::find($req['speaker_id']);

        $presentation = Presentation::factory()->make([
            'name' => $req['name'],
            'description' => $req['description'],
            'start_date' => $req['start_date'],
            'end_date' => $req['end_date'],
            'speaker_id' => $speaker->id,
            'stage_id' => $stage->id
        ]);

        $others = $stage->presentations()->get()->all();

        $overlaps = [];

        foreach ($others as $other) {
            if ($presentation->start_date->lt($other->end_date) && $presentation->end_date->gt($other->start_date)) {
                array_push($overlaps, $other);
            }
        }

        if (sizeof($overlaps) > 0) {
            return response()->json([
                'code' => 1,
                'message' => "Overlapping presentations on this stage",
                'overlaps' => $overlaps
            ]);
        }

        $presentation->save();

        return response()->json(['presentation' => $presentation]);
    }

}
