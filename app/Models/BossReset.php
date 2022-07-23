<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $boss_id
 * @property \Carbon\CarbonImmutable $reset_at
 * @property bool $adjust
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 */
class BossReset extends Model
{
    protected $casts = [
        'adjust' => 'bool',
        'boss_id' => 'int',
        'reset_at' => 'datetime',
    ];

    protected $fillable = [
        'adjust',
        'reset_at',
    ];
}
