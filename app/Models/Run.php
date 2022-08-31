<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $times_decayed
 * @property \Carbon\CarbonImmutable $created_at
 * @property \Carbon\CarbonImmutable $updated_at
 */
class Run extends Model
{
    public $casts = [
        'times_decayed' => 'int',
    ];

    public $guarded = false;
}
