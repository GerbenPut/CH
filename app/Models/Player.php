<?php

namespace App\Models;

use App\Enums\ClassType;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property \App\Enums\ClassType $class_type
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 */
class Player extends Model
{
    protected $casts = [
        'class_type' => ClassType::class,
    ];

    protected $fillable = [
        'class_type',
        'name',
    ];
}
