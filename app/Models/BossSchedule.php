<?php

namespace App\Models;

use App\Enums\BossType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $name
 * @property \App\Enums\BossType $type
 * @property \Carbon\CarbonImmutable|null $reset_at
 * @property \Carbon\CarbonImmutable|null $open
 * @property \Carbon\CarbonImmutable|null $closed
 * @property-read \App\Models\Boss $boss
 */
class BossSchedule extends Model
{
    protected $casts = [
        'closed' => 'datetime',
        'open' => 'datetime',
        'reset_at' => 'datetime',
        'type' => BossType::class,
    ];

    protected $table = 'boss_schedule';

    public $timestamps = false;

    public function boss(): BelongsTo
    {
        return $this->belongsTo(Boss::class, 'id');
    }
}
