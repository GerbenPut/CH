<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BossTimer extends Model
{
    public $casts = [
        'date' => 'datetime',
        'closed' => 'int',
        'open' => 'int',
    ];
}
