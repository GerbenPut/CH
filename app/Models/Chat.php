<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Boss> $bosses
 */
class Chat extends Model
{
    public function bosses(): BelongsToMany
    {
        return $this->belongsToMany(Boss::class)
            ->using(BossChat::class)
            ->withPivot(['camp_worth', 'kill_worth'])
            ->withTimestamps();
    }

    public static function DKP(): static
    {
        return static::query()
            ->where('name', __FUNCTION__)
            ->sole();
    }

    public static function QKP(): static
    {
        return static::query()
            ->where('name', __FUNCTION__)
            ->sole();
    }
}
