<?php

use App\Support\Commands\AddPlayerCommand;
use App\Support\Commands\AdjustCommand;
use App\Support\Commands\KillsCommand;
use App\Support\Commands\ChangeCommand;
use App\Support\Commands\DueCommand;
use App\Support\Commands\GroupIdCommand;
use App\Support\Commands\IncrementKillCommand;
use App\Support\Commands\RemovePlayerCommand;
use App\Support\Commands\ResetCommand;
use App\Support\Commands\Score\AddCommand;
use App\Support\Commands\Score\ResetCommand as ResetScoreCommand;
use App\Support\Commands\Score\SubtractCommand;
use App\Support\Commands\TimersCommand;
use App\Support\Commands\UnknownCommand;
use App\Support\Commands\AttendsCommand;

return [
    'commands' => [
        '_' => [
            GroupIdCommand::class,
        ],
        'admin' => [
            AddPlayerCommand::class,
            AddCommand::class, // Must be behind AddPlayerCommand
            ChangeCommand::class,
            RemovePlayerCommand::class,
            ResetScoreCommand::class,
            SubtractCommand::class,
        ],
        'attends_dkp' => [
            AttendsCommand::class,
            KillsCommand::class,
            IncrementKillCommand::class,
        ],
        'attends_qkp' => [
            AttendsCommand::class,
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
