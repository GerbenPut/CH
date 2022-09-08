<?php

namespace App\Support\Commands\Score;

use App\Models\Attend;
use App\Models\Chat;
use App\Models\Player;
use App\Support\Commands\Command;

class ResetCommand extends Command
{
    protected string $name = 'reset';

    public function handle(array $args, string $command): void
    {
        if (count($args) < 2) {
            $this->reply('Please provide a player name and chat');

            return;
        }

        /** @var \App\Models\Chat $chat */
        $chat = Chat::query()
            ->where('name', strtoupper($args[1]))
            ->firstOrFail();

        /** @var \App\Models\Player $player */
        $player = Player::query()
            ->where('name', $args[0])
            ->firstOrFail();

        /** @var \App\Models\Attend $attend */
        $attend = Attend::query()
            ->whereBelongsTo($chat)
            ->whereBelongsTo($player)
            ->firstOrNew();

        if (!$attend->exists) {
            $this->reply('Attends reset');

            return;
        }

        $attend->score = 0;
        $attend->save();

        $this->reply('Attends reset');
    }
}
