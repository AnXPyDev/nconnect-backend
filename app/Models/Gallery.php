<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    public function images() {
        return $this->belongsToMany(Resource::class, 'gallery_resource_pivot');
    }
}
