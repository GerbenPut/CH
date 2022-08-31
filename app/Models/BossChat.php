<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $boss_id
 * @property int $chat_id
 * @property int $kill_worth
 * @property int $camp_worth
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Boss $boss
 * @property-read \App\Models\Chat $chat
 */
class BossChat extends Pivot
{
    public function boss(): BelongsTo
    {
        return $this->belongsTo(Boss::class);
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }
}
