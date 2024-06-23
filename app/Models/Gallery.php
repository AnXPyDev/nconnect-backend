<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $casts = [
        'public' => 'boolean'
    ];

    public function images() {
        return $this->belongsToMany(Resource::class, 'gallery_resource_pivot');
    }

    public function addImage($resource) {
        if ($resource->type != 'image') {
            return response()->json([
                'code' => Codes::BADINPUT,
                'message' => "Resource is not of type image"
            ]);
        }

        $pivot = GalleryResourcePivot::where('gallery_id', $this->id)->where('resource_id', $resource->id);

        if ($pivot->exists()) {
            return;
        }

        $pivot = GalleryResourcePivot::factory()->create([
            'gallery_id' => $this->id,
            'resource_id' => $resource->id
        ]);
    }
}
