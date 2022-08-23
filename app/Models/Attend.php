<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $player_id
 * @property int $boss_chat_id
 * @property int $kills
 * @property int $camps
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\BossChat $bossChat
 * @property-read \App\Models\Player $player
 * @property-read \App\Models\Run $run
 */
class Attend extends Model
{
    protected $casts = [
        'boss_chat_id' => 'int',
        'camps' => 'int',
        'kills' => 'int',
        'player_id' => 'int',
    ];

    public function bossChat(): BelongsTo
    {
        return $this->belongsTo(BossChat::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function run(): BelongsTo
    {
        return $this->belongsTo(Run::class);
    }
}
