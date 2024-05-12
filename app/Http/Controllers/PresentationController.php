<?php

namespace App\Http\Controllers;

use App\Models\Presentation;
use App\Models\Speaker;
use App\Models\Stage;
use Illuminate\Http\Request;

class PresentationController extends Controller
{

    function create() {
        $req = request()->all();

        $stage = Stage::find($req['stage']);
        $speaker = Speaker::find($req['speaker']);

        $presentation = Presentation::factory()->make([
            'name' => $req['name'],
            'description' => $req['description'],
            'start_date' => $req['start_date'],
            'end_date' => $req['end_date'],
            'speaker' => $speaker,
            'stage' => $stage
        ]);

        $other = $stage->presentations();

        foreach ($other as $p) {
            if ($presentation->start_date < $other->end_date && $presentation->end_date > $other->start_date) {
                return response()->abort();
            }
        }

        $presentation->save();

        return response()->json(['presentation' => $presentation]);
    }

}
