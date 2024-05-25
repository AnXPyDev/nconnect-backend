<?php

namespace App\Http\Controllers;

use App\Http\Codes;
use App\Models\Stage;
use App\Models\Timeslot;
use Illuminate\Http\Request;

class TimeslotController extends Controller
{
    function get_overlaps(Stage $stage, Timeslot $timeslot) {
        $overlaps = [];
        $others = $stage->timeslots()->get()->all();
        foreach ($others as $other) {
            if ($timeslot->id == $other->id) {
                continue;
            }

            if ($timeslot->start_at->lt($other->end_at) && $timeslot->end_at->gt($other->start_at)) {
                $overlaps[] = $other;
            }
        }

        return $overlaps;
    }

    function validate_overlaps(Stage $stage, Timeslot $timeslot) {
        $overlaps = $this->get_overlaps($stage, $timeslot);

        if (count($overlaps) > 0) {
            response()->json([
                'code' => Codes::OVERLAP,
                'message' => "Timeslot overlaps others",
                'overlaps' => $overlaps
            ])->throwResponse();
        }
    }

    function create() {
        $req = request()->validate([
            'end_at'=> 'required|date',
            'start_at'=> 'required|date',
            'stage_id' => 'required|exists:stages,id'
        ]);

        $stage = Stage::find($req['stage_id']);

        $timeslot = Timeslot::factory()->make([
            'start_at' => $req['start_at'],
            'end_at' => $req['end_at'],
            'stage_id' => $req['stage_id']
        ]);

        $this->validate_overlaps($stage, $timeslot);

        $timeslot->save();

        return response()->json([
            'timeslot' => $timeslot
        ]);
    }

    function edit() {
        $req = request()->validate([
            'id' => 'required|exists:timeslots,id',
            'end_at'=> 'required|date',
            'start_at'=> 'required|date'
        ]);

        $timeslot = Timeslot::find($req['id']);

        $timeslot->start_at = $req['start_at'];
        $timeslot->end_at = $req['end_at'];

        $stage = $timeslot->stage()->first();

        $this->validate_overlaps($stage, $timeslot);

        $timeslot->save();

        return response()->json([
            'timeslot' => $timeslot
        ]);

    }
    function delete() {
        $req = request()->validate([
            'id' => 'required|exists:timeslots,id'
        ]);

        $timeslot = Timeslot::find($req['id']);

        $timeslot->delete();

        return response()->json();
    }

    function presentation() {
        $req = request()->validate([
            'id' => 'required|exists:timeslots,id'
        ]);

        $timeslot = Timeslot::find($req['id']);

        $presentation = $timeslot->presentation()->get()->first();

        return response()->json([
            'presentation' => $presentation
        ]);
    }
}
