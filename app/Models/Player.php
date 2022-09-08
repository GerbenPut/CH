<?php

namespace App\Models;

use App\Enums\ClassType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $name
 * @property \App\Enums\ClassType $class_type
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Kill> $kills
 * @property-read int|null $kills_count
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

    public function kills(): HasMany
    {
        return $this->hasMany(Kill::class);
    }
}
