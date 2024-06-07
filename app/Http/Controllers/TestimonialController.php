<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{

    function create() {
        $req = $this->validate([
            'author' => 'required',
            'description' => 'required',
            'image_id' => 'nullable|exists:resources,id',
        ]);

        $testimonial = Testimonial::factory()->make([
            "author" => $req["author"],
            "description" => $req["description"],
            "image_id" => $req["image_id"],
        ]);

        $testimonial->save();

        return response()->json(['testimonial' => $testimonial]);
    }

    function index() {
        return response()->json(['testimonials' => Testimonial::all()]);
    }

    function edit() {
        $req = $this->validate([
            'id' => 'required|exists:testimonials,id',
            'author' => 'required',
            'description' => 'required'
        ]);

        $testimonial = Testimonial::find($req["id"]);

        $testimonial->author = $req["author"];
        $testimonial->description = $req["description"];
        $testimonial->image_id = $req["image_id"];

        $testimonial->save();

        return response()->json(['testimonial' => $testimonial]);
    }

    function delete() {
        $req = $this->validate([
            'id' => 'required|exists:testimonials,id'
        ]);

        $testimonial = Testimonial::find($req["id"]);

        $testimonial->delete();

        return response()->json();
    }
}
