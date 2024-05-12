<?php

namespace App\Http\Controllers;

use App\Models\Presentation;
use App\Models\Speaker;
use App\Models\Stage;
use App\Models\Timeslot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PresentationController extends Controller
{

    function create() {
        $req = request()->validate([
            'name' => 'required|string',
            'description' => 'string',
            'long_description' => 'string',
            'speaker_id' => 'required|exists:speakers,id',
            'timeslot_id' => 'required|exists:timeslots,id'
        ]);

        $timeslot = Timeslot::find($req['timeslot_id']);

        if ($timeslot->presentation()->exists()) {
            return response()->json([
                'code' => 1,
                'message' => "Timeslot already used by presentation",
                'presentation' => $timeslot->presentation()->get()
            ]);
        }

        $presentation = Presentation::factory()->make([
            'name' => $req['name'],
            'description' => $req['description'],
            'long_description' => $req['long_description'],
            'speaker_id' => $req['speaker_id'],
            'timeslot_id' => $req['timeslot_id']
        ]);

        $presentation->save();

        return response()->json(['presentation' => $presentation]);
    }

}
