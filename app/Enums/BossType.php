<?php

namespace App\Enums;

enum BossType: string
{
    case DragonLord = 'dl';
    case ExaltedDragonLord = 'edl';
    case Legacy = 'legacy';
    case Raid = 'raid';
}
