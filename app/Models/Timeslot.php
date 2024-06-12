<?php

namespace App\Models;

use App\Http\Codes;
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
        return $this->belongsTo(Presentation::class);
    }

    public function stage() {
        return $this->belongsTo(Stage::class);
    }

    public function setPresentation(int $presentation_id) {
        if (is_null($presentation_id)) {
            $this->presentation_id = null;
            return;
        }

        if ($this->presentation_id == $presentation_id) {
            return;
        }

        $presentation = Presentation::find($presentation_id);

        if ($presentation->timeslot()->exists()) {
            response()->json([
                'code' => Codes::OCCUPIED,
                'message' => 'Presentation already in timeslot'
            ])->throwResponse();
        }

        $this->presentation_id = $presentation->id;
    }

    public function getOverlaps() {
        $this->load('stage');
        $stage = $this->stage;

        $overlaps = [];
        $others = $stage->timeslots()->get()->all();
        foreach ($others as $other) {
            if ($this->id == $other->id) {
                continue;
            }

            if ($this->start_at->lt($other->end_at) && $this->end_at->gt($other->start_at)) {
                $overlaps[] = $other;
            }
        }

        return $overlaps;
    }

    function validateOverlaps() {
        $overlaps = $this->getOverlaps();

        if (count($overlaps) > 0) {
            response()->json([
                'code' => Codes::OVERLAP,
                'message' => "Timeslot overlaps others",
                'overlaps' => $overlaps
            ])->throwResponse();
        }
    }
}
