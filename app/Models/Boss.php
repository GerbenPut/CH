<?php

namespace App\Models;

use App\Enums\BossType;
use App\Models\Builders\BossBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $name
 * @property \App\Enums\BossType $type
 * @property int $open
 * @property int $closed
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BossAlias> $aliases
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Chat> $chats
 * @property-read \App\Models\BossReset|null $latestReset
 * @method static \App\Models\Builders\BossBuilder query()
 */
class Boss extends Model
{
    public $casts = [
        'closed' => 'int',
        'open' => 'int',
        'type' => BossType::class,
        'worth' => 'int',
    ];

    public function aliases(): HasMany
    {
        return $this->hasMany(BossAlias::class);
    }

    public function chats(): BelongsToMany
    {
        return $this->belongsToMany(Chat::class)
            ->using(BossChat::class)
            ->withPivot(['kill_worth'])
            ->withTimestamps();
    }

    public function latestReset(): HasOne
    {
        return $this->hasOne(BossReset::class)->latestOfMany();
    }

    public function newEloquentBuilder($query): BossBuilder
    {
        return new BossBuilder($query);
    }
}
