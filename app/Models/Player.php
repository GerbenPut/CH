<?php

namespace App\Models;

use App\Enums\ClassType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property \App\Enums\ClassType $class_type
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attend> $attends
 * @property-read int|null $attends_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PointsPerRun> $pointsPerRun
 * @property-read int|null $points_per_run_count
 */
class Player extends Model
{
    protected $casts = [
        'class_type' => ClassType::class,
    ];

    protected $fillable = [
        'class_type',
        'name',
    ];

    public function attends(): HasMany
    {
        return $this->hasMany(Attend::class);
    }

    public function pointsPerRun(): HasMany
    {
        return $this->hasMany(PointsPerRun::class);
    }
}
