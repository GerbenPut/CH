<?php

namespace App\Support\Commands\Score;

use App\Models\Attend;
use App\Models\Chat;
use App\Models\Player;
use App\Support\Commands\Command;

class AddCommand extends Command
{
    protected string $name = 'add*';

    public function handle(array $args, string $command): void
    {
        if (preg_match('/^add(.+)$/', $command, $matches) !== 1) {
            return;
        }

        if (count($args) < 2 || !ctype_digit($args[1])) {
            $this->reply('Please provide a player name and amount');

            return;
        }

        [, $chatName] = $matches;
        $chatName = strtoupper($chatName);

        /** @var \App\Models\Chat $chat */
        $chat = Chat::query()
            ->where('name', $chatName)
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

        $attend->chat()->associate($chat);
        $attend->player()->associate($player);
        $attend->score += $args[1];

        $attend->save();

        $this->reply('Attends added');
    }
}
