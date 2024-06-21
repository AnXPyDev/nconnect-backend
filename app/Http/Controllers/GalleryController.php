<?php

namespace App\Http\Controllers;

use App\Http\Codes;
use App\Models\GalleryResourcePivot;
use App\Models\Resource;
use Illuminate\Http\Request;
use App\Models\Gallery;

class GalleryController extends Controller
{

    function create() {
        $req = $this->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'thumbnail_id' => 'nullable|exists:resources,id',
            'public' => 'nullable|boolean'
        ]);

        $gallery = Gallery::factory()->create([
            'name' => $req['name'],
            'description' => $req['description'] ?? null,
            'thumbnail_id' => $req['thumbnail_id'] ?? null,
            'public' => $req['public'] ?? null
        ]);

        return response()->json([
            'gallery' => $gallery
        ]);
    }

    function edit() {
        $req = $this->validate([
            'id' => 'required|exists:galleries,id',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'thumbnail_id' => 'nullable|exists:resources,id',
            'public' => 'nullable|boolean'
        ]);

        $gallery = Gallery::find($req['id']);

        $gallery->name = $req['name'];
        $gallery->description = $req['description'] ?? null;
        $gallery->thumbnail_id = $req['thumbnail_id'] ?? null;
        $gallery->public = $req['public'] ?? false;

        $gallery->save();

        return response()->json([
            'gallery' => $gallery
        ]);
    }

    function delete() {
        $req = $this->validate([
            'id' => 'required|exists:galleries,id'
        ]);

        $gallery = Gallery::find($req['id']);

        $gallery->delete();

        return response()->json();

    }

    function index() {
        return response()->json([
            'galleries' => Gallery::all()
        ]);
    }

    function publicindex() {
        return response()->json([
            'galleries' => Gallery::where('public', true)->get()
        ]);
    }

    function images() {
        $req = $this->validate([
            'id' => 'required|exists:galleries,id'
        ]);

        $gallery = Gallery::find($req['id']);

        return response()->json([
            'images' => $gallery->images()->get()->all()
        ]);
    }

    function addimage() {
        $req = $this->validate([
            'id' => 'required|exists:galleries,id',
            'resource_id' => 'required|exists:resources,id'
        ]);


        $gallery = Gallery::find($req['id']);
        $resource = Resource::find($req['resource_id']);

        $gallery->addImage($resource);

        $pivot = GalleryResourcePivot::factory()->create([
            'gallery_id' => $gallery->id,
            'resource_id' => $resource->id
        ]);

        return response()->json([
            'image' => $resource
        ]);
    }

    function createimage() {
        $req = $this->validate([
            'id' => 'required|exists:galleries,id',
            'name' => 'required|string'
        ]);

        $resource = Resource::factory()->create([
            'name' => $req['name'],
            'type' => 'image'
        ]);

        $pivot = GalleryResourcePivot::factory()->create([
            'gallery_id' => $req['id'],
            'resource_id' => $resource->id
        ]);

        return response()->json([
            'image' => $resource
        ]);
    }

    function deleteimage() {
        $req = $this->validate([
            'id' => 'required|exists:galleries,id',
            'resource_id' => 'required|exists:resources,id'
        ]);

        $pivot = GalleryResourcePivot::where('gallery_id', $req['id'])->where('resource_id', $req['resource_id']);

        if (!$pivot->exists()) {
            return response()->json([
                'code' => Codes::EMPTY,
                'message' => 'Resource not in gallery'
            ]);
        }

        $pivot->first()->delete();

        return response()->json();
    }
}
