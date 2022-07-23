<?php

namespace App\Models;

use App\Enums\BossType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $name
 * @property \App\Enums\BossType $type
 * @property int $open
 * @property int $closed
 * @property int $worth
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\BossReset|null $latestReset
 */
class Boss extends Model
{
    public $casts = [
        'closed' => 'int',
        'open' => 'int',
        'type' => BossType::class,
        'worth' => 'int',
    ];

    public function latestReset(): HasOne
    {
        return $this->hasOne(BossReset::class)->latestOfMany();
    }
}
