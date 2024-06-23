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
 * @property string $name
 * @property string|null $description
 * @property string|null $long_description
 * @property int $timeslot_id
 * @property int $speaker_id
 * @method static \Database\Factories\PresentationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Presentation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Presentation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Presentation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Presentation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Presentation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Presentation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Presentation whereLongDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Presentation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Presentation whereSpeakerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Presentation whereTimeslotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Presentation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Presentation extends Model
{
    use HasFactory;

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'generic' => 'boolean',
        'allow_registration' => 'boolean'
    ];

    public function timeslots() {
        return $this->hasMany(Timeslot::class);
    }

    public function speaker() {
        return $this->belongsTo(Speaker::class);
    }
}
