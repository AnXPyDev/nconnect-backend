<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timeslot extends Model
{
    use HasFactory;

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime'
    ];

    public function presentation() {
        return $this->hasOne(Presentation::class);
    }

    public function stage() {
        return $this->belongsTo(Stage::class);
    }

    public function speaker() {
        return $this->hasOne(Speaker::class);
    }
}
