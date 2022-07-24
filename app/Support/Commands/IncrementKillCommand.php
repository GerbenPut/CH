<?php

namespace App\Support\Commands;

use App\Enums\ClassType;
use App\Models\Boss;
use App\Models\Player;

class IncrementKillCommand extends Command
{
    protected string $name = '*';

    public function handle(array $args, string $command): void
    {
        if (count($args) < 1) {
            $this->reply('Please specify a boss.');

            return;
        }

        /** @var \App\Models\Player $player */
        $player = Player::query()
            ->where('name', $command)
            ->firstOrFail();

        /** @var \App\Models\Boss $boss */
        $boss = Boss::query()
            ->where('name', $args[0])
            ->firstOrFail();

        /** @var \App\Models\KillCount $attend */
        $attend = $player->killCounts()->firstOrNew(['boss_id' => $boss->id]);

        $attend->kills++;
        $attend->boss()->associate($boss);
        $attend->save();

        $this->reply('Kills incremented');
    }
}
