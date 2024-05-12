<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use App\Models\Timeslot;
use Illuminate\Http\Request;

class TimeslotController extends Controller
{
    function create() {
        $req = request()->validate([
            'end_at'=> 'required|date_format:Y-m-d H:i:s',
            'start_at'=> 'required|date_format:Y-m-d H:i:s',
            'stage_id' => 'required|exists:stages,id'
        ]);

        $stage = Stage::find($req['stage_id']);

        $timeslot = Timeslot::factory()->make([
            'start_at' => $req['start_at'],
            'end_at' => $req['end_at'],
            'stage_id' => $req['stage_id']
        ]);

        $others = $stage->timeslots()->get()->all();

        $overlaps = [];

        foreach ($others as $other) {
            if ($timeslot->start_at->lt($other->end_at) && $timeslot->end_at->gt($other->start_at)) {
                array_push($overlaps, $other);
            }
        }

        if (count($overlaps) > 0) {
            return response()->json([
                'code' => 1,
                'message' => "Timeslot overlaps others",
                'overlaps' => $overlaps
            ]);
        }

        $timeslot->save();

        return response()->json([
            'timeslot' => $timeslot
        ]);
    }
}
