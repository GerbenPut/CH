<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $player_id
 * @property int $boss_id
 * @property int $kills
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 */
class KillCount extends Model
{
    protected $casts = [
        'boss_id' => 'int',
        'kills' => 'int',
        'player_id' => 'int',
    ];
}
