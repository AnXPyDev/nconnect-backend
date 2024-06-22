<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StageController extends Controller
{
    function create() {
        $req = $this->validate([
            'name' => 'required',
        ]);

        $stage =  Stage::factory()->make([
            "name" => $req["name"],
        ]);

        $stage->save();

        return response()->json(['stage' => $stage]);
    }

    function index() {
        return response()->json(['stages' => Stage::all()]);
    }

    function edit() {
        $req = $this->validate([
            "id" => "required|exists:stages,id",
            "name" => "required"
        ]);

        $stage = Stage::find($req["id"]);

        $stage->name = $req["name"];

        $stage->save();

        return response()->json(['stage' => $stage]);
    }

    function delete() {
        $req = $this->validate([
            'id' => 'required|exists:stages,id'
        ]);

        $stage = Stage::find($req["id"]);

        $stage->delete();

        return response()->json();
    }

    function timeslots() {
        $req = $this->validate([
            'id' => 'required|exists:stages,id'
        ]);


        $stage = Stage::find($req["id"]);

        return response()->json(['timeslots' => $stage->timeslots()->get()]);
    }

    function scheduleinfo() {
        $req = $this->validate([
            'id' => 'required|exists:stages,id'
        ]);

        $stage = Stage::find($req["id"]);

        $timeslots = $stage->timeslots()
            ->whereHas('presentation')
            ->with('presentation.speaker')
            ->get();


        /*
        foreach ($timeslots as $timeslot) {
            $timeslot->presentation->load('speaker');
            Log::info(var_export($timeslot, true));
            $timeslot->append('remaining_capacity');
            Log::info(var_export($timeslot, true));
        }

        GRATULUJEM CLOVEKU ZA KTOREHO NEJDE APPENDUT ATTRIUT LEBO UNLOADNE RELATIONSHIP Z NEJAKEHO DOVODU TAK TO MUSIM DAT DO ARRAYU
        */


        $idc_array = [];

        foreach ($timeslots as $timeslot) {
            $arr = $timeslot->toArray();
            $arr['remaining_capacity'] = $timeslot->remaining_capacity; // CTJB
            $idc_array[] = $arr;
        }


        return response()->json([
            'timeslots' => $idc_array
        ]);
    }
}
