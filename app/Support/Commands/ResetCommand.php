<?php

namespace App\Support\Commands;

use LINE\LINEBot\Event\MessageEvent\TextMessage;
use App\Models\BossTimer;

class ResetCommand extends Command
{
    protected string $name = 'reset';

    public function handle(TextMessage $event, array $args): void
    {
        if (count($args) === 0) {
            $this->reply('Please specify a boss.');

            return;
        }

        $raids = ['necromancer', 'proteus', 'gelebron', 'dhiothu', 'bloodthorn', 'hrungnir', 'mordris'];

        if (in_array($args[0], $raids)) {
            $this->reply('You are not allowed to those bosses yet. *insert evil smiley*');

            return;
        }

        $timer = BossTimer::query()
            ->where('name', $args[0])
            ->firstOrFail();

        $timer->date = now();
        $timer->save();

        $this->reply($timer->name . ' has been reset.');
    }
}
