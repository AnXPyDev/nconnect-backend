<?php

namespace App\Http\Controllers;

use App\Http\Codes;
use App\Models\GalleryResourcePivot;
use App\Models\Resource;
use Illuminate\Http\Request;
use App\Models\Conference;

class ConferenceController extends Controller
{
    function edit() {
        $req = $this->validate([
            'date' => 'required|date',
            'state' => 'required|integer',

            'subtitle' => 'required|string',

            'about_title' => 'required|string',
            'about_text' => 'required|string',

            'presentation_title' => 'required|string',
            'presentation_subtitle' => 'required|string',

            'location_name' => 'required|string',
            'location_city' => 'required|string',
            'location_full' => 'required|string',
            'location_link' => 'required|string',
            'location_map_embed' => 'required|string',

            'contact' => 'required|array'
        ]);

        $conference = Conference::first();

        $conference->date = $req['date'];
        $conference->state = $req['state'];

        $conference->subtitle = $req['subtitle'];

        $conference->about_title = $req['about_title'];
        $conference->about_text = $req['about_text'];

        $conference->presentation_title = $req['presentation_title'];
        $conference->presentation_subtitle = $req['presentation_subtitle'];

        $conference->location_name = $req['location_name'];
        $conference->location_city = $req['location_city'];
        $conference->location_full = $req['location_full'];
        $conference->location_link = $req['location_link'];
        $conference->location_map_embed = $req['location_map_embed'];

        $conference->contact = $req['contact'];

        $conference->save();

        return response()->json([
            'conference' => $conference
        ]);
    }

    function get() {
        return response()->json([
            'conference' => Conference::first()
        ]);
    }

}
