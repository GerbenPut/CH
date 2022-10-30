<?php

namespace App\Support\Commands\Score;

use App\Models\Chat;
use App\Models\Kill;
use App\Models\Player;
use App\Support\Commands\Command;

class KillsResetCommand extends Command
{
    protected string $name = 'reset-kills';

    public function handle(array $args): void
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

        /** @var \App\Models\Kill $kill */
        $kill = Kill::query()
            ->whereBelongsTo($chat, 'bossChat.chat')
            ->whereBelongsTo($player)
            ->firstOrNew();

        if (!$kill->exists) {
            $this->reply('Kills reset');

            return;
        }

        $kill->kills = 0;
        $kill->save();

        $this->reply('Kills reset');
    }
}
