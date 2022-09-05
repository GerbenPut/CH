<?php

namespace App\Enums;

enum BossType: string
{
    case DragonLord = 'dl';
    case ExaltedDragonLord = 'edl';
    case Legacy = 'legacy';
    case Meteoric = 'meteoric';
    case Raid = 'raid';
    case RingBoss = 'rb';
}
