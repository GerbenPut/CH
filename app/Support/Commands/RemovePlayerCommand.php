<?php

namespace App\Support\Commands;

use App\Models\Player;

class RemovePlayerCommand extends Command
{
    protected string $name = 'removeplayer';

    public function handle(array $args): void
    {
        if (!isset($args[0])) {
            $this->reply('Please specify a name.');

            return;
        }

        $result = Player::query()
            ->where('name', $args[0])
            ->delete();

        if ($result > 0) {
            $this->reply('Player deleted!');
        } else {
            $this->reply('Player not found!');
        }
    }
}
