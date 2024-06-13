<?php

namespace App\Http\Controllers;

use App\Http\Codes;
use App\Models\Presentation;
use App\Models\Stage;
use App\Models\Timeslot;
use http\Env\Response;
use Illuminate\Http\Request;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;

class TimeslotController extends Controller
{

    function create() {
        $req = $this->validate([
            'end_at'=> 'required|date',
            'start_at'=> 'required|date',
            'stage_id' => 'required|exists:stages,id',
            'presentation_id' => 'nullable|exists:presentations,id'
        ]);

        $timeslot = Timeslot::factory()->make([
            'start_at' => $req['start_at'],
            'end_at' => $req['end_at'],
            'stage_id' => $req['stage_id'],
        ]);

        $timeslot->validateOverlaps();
        $timeslot->setPresentation($req['presentation_id'] ?? null);

        $timeslot->save();

        $timeslot->load('presentation');

        return response()->json([
            'timeslot' => $timeslot
        ]);
    }

    function edit() {
        $req = $this->validate([
            'id' => 'required|exists:timeslots,id',
            'end_at' => 'required|date',
            'start_at' => 'required|date',
            'presentation_id' => 'nullable|exists:presentations,id'
        ]);

        $timeslot = Timeslot::find($req['id']);

        $timeslot->start_at = $req['start_at'];
        $timeslot->end_at = $req['end_at'];

        $timeslot->validateOverlaps();
        $timeslot->setPresentation($req['presentation_id'] ?? null);

        $timeslot->save();

        $timeslot->load('presentation');

        return response()->json([
            'timeslot' => $timeslot
        ]);

    }
    function delete() {
        $req = $this->validate([
            'id' => 'required|exists:timeslots,id'
        ]);

        $timeslot = Timeslot::find($req['id']);

        $timeslot->delete();

        return response()->json();
    }

    function presentation() {
        $req = $this->validate([
            'id' => 'required|exists:timeslots,id'
        ]);

        $timeslot = Timeslot::find($req['id']);


        $has_presentation = $timeslot->presentation();
        if ($has_presentation->exists()) {
            return response()->json([
                'presentation' => $has_presentation->first()
            ]);
        }

        return response()->json([
            'code' => Codes::EMPTY,
            'message' => 'Timeslot has no presentation assigned'
        ]);
    }


}
