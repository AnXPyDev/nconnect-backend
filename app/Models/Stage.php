<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    use HasFactory;

    function overlappingPresentations(Presentation $presentation) {
        $all = $this->hasMany(Presentation::class)->get();
        $all->jsonSerialize();
    }

    function presentations() {
        return $this->hasMany(Presentation::class)->all();
    }
}
