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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\KillCount> $killCounts
 * @property-read int|null $kill_counts_count
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

    public function killCounts(): HasMany
    {
        return $this->hasMany(KillCount::class);
    }
}
