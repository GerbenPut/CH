<?php

namespace App\Support\Commands\Score;

use App\Models\Chat;
use App\Models\Kill;
use App\Support\Commands\Command;

class AllKillsResetCommand extends Command
{
    protected string $name = 'reset-all-kills';

    public function handle(array $args): void
    {
        if (count($args) < 1) {
            $this->reply('Please provide a chat');

            return;
        }

        /** @var \App\Models\Chat $chat */
        $chat = Chat::query()
            ->where('name', strtoupper($args[0]))
            ->firstOrFail();

        Kill::query()
            ->whereBelongsTo($chat, 'bossChat.chat')
            ->each(function (Kill $kill) {
                $kill->kills = 0;

                $kill->save();
            });

        $this->reply('Kills reset');
    }
}
