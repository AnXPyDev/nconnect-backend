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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Presentation> $presentations
 * @property-read int|null $presentations_count
 * @method static \Database\Factories\SpeakerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Speaker newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Speaker newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Speaker query()
 * @method static \Illuminate\Database\Eloquent\Builder|Speaker whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Speaker whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Speaker whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Speaker whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Speaker whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Speaker extends Model
{
    use HasFactory;

    public function presentations() {
        return $this->hasMany(Presentation::class);
    }
}
