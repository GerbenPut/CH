<?php

namespace App\Support\Commands;

use LINE\LINEBot\Event\MessageEvent\TextMessage;
use App\Models\BossTimer;
use Carbon\CarbonInterface;

class ChangeCommand extends Command
{
    public function handle(TextMessage $event, array $args): void
    {
        if (!isset($args[0])) {
            $this->reply('Please specify a boss name to change.');

            return;
        }

        $name = array_shift($args);

        $args = array_filter($args, 'ctype_digit');

        if (count($args) !== 2) {
            $this->reply('Change requires two integer arguments after the name, open and close respectively');

            return;
        }

        $timer = BossTimer::query()
            ->where('name', $name)
            ->firstOrFail();

        $timer->open = intval($args[0]);
        $timer->closed = intval($args[1]);
        $timer->save();

        $this->reply('Boss timer changed.');
    }
}
