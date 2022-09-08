<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $player_id
 * @property int $chat_id
 * @property float $score
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Chat $chat
 * @property-read \App\Models\Player $player
 */
class Attend extends Model
{
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
