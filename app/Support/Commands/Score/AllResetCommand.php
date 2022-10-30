<?php

namespace App\Support\Commands\Score;

use App\Models\Attend;
use App\Models\Chat;
use App\Support\Commands\Command;

class AllResetCommand extends Command
{
    protected string $name = 'reset-all';

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

        Attend::query()
            ->whereBelongsTo($chat)
            ->each(function (Attend $attend) {
                $attend->score = 0;

                $attend->save();
            });

        $this->reply('Attends reset');
    }
}
