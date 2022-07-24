<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $player_id
 * @property int $boss_id
 * @property int $kills
 * @property int $camps
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Boss $boss
 * @property-read \App\Models\Player $player
 */
class KillCount extends Model
{
    protected $casts = [
        'boss_id' => 'int',
        'camps' => 'int',
        'kills' => 'int',
        'player_id' => 'int',
    ];

    public function boss(): BelongsTo
    {
        return $this->belongsTo(Boss::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
