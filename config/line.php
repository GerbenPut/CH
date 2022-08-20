<?php

use App\Support\Commands\AddPlayerCommand;
use App\Support\Commands\AdjustCommand;
use App\Support\Commands\KillsCommand;
use App\Support\Commands\ChangeCommand;
use App\Support\Commands\ClassAttendsCommand;
use App\Support\Commands\DueCommand;
use App\Support\Commands\GroupIdCommand;
use App\Support\Commands\IncrementKillCommand;
use App\Support\Commands\RemovePlayerCommand;
use App\Support\Commands\ResetCommand;
use App\Support\Commands\TimersCommand;
use App\Support\Commands\UnknownCommand;

return [
    'commands' => [
        '_' => [
            GroupIdCommand::class,
        ],
        'admin' => [
            AddPlayerCommand::class,
            ChangeCommand::class,
            ClassAttendsCommand::class,
            RemovePlayerCommand::class,
        ],
        'attends_dkp' => [
            KillsCommand::class,
            IncrementKillCommand::class,
        ],
        'attends_qkp' => [
            KillsCommand::class,
            IncrementKillCommand::class,
        ],
        'raid_timers' => [
            AdjustCommand::class,
            DueCommand::class,
            ResetCommand::class,
            TimersCommand::class,
            UnknownCommand::class,
        ],
        'timers' => [
            AdjustCommand::class,
            DueCommand::class,
            ResetCommand::class,
            TimersCommand::class,
            UnknownCommand::class,
        ],
    ],

    'group_ids' => [
        'admin' => env('ADMIN_GROUP_ID'),
        'attends_dkp' => env('ATTENDS_DKP_GROUP_ID'),
        'attends_qkp' => env('ATTENDS_QKP_GROUP_ID'),
        'raid_timers' => env('RAID_TIMERS_GROUP_ID'),
        'timers' => env('TIMERS_GROUP_ID'),
    ],
];
