<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $boss_id
 * @property int $player_id
 * @property int $run_id
 * @property float $points
 * @property-read \App\Models\Boss $boss
 * @property-read \App\Models\Player $player
 * @property-read \App\Models\Run $run
 */
class PointsPerRun extends Model
{
    protected $casts = [
        'boss_id' => 'int',
        'player_id' => 'int',
        'points' => 'float',
        'run_id' => 'int',
    ];

    protected $table = 'points_per_run';

    public $timestamps = false;

    public function boss(): BelongsTo
    {
        return $this->belongsTo(Boss::class);
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