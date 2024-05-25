<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon $start_at
 * @property \Illuminate\Support\Carbon $end_at
 * @property int|null $presentation_id
 * @property int $stage_id
 * @property-read \App\Models\Presentation|null $presentation
 * @property-read \App\Models\Speaker|null $speaker
 * @property-read \App\Models\Stage|null $stage
 * @method static \Database\Factories\TimeslotFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot query()
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot whereEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot wherePresentationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot whereStageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timeslot whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
}
