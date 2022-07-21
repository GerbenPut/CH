<?php

namespace App\Support\Commands;

use LINE\LINEBot\Event\MessageEvent\TextMessage;
use App\Models\BossTimer;

class AdjustCommand extends Command
{
    protected string $name = 'adjust';

    public function handle(TextMessage $event, array $args): void
    {
        if (!isset($args[0])) {
            $this->reply('Please specify a boss name to change.');

            return;
        }

        $name = array_shift($args);

        if (!isset($args[0]) || !in_array($args[0], ['add', 'sub'])) {
            $this->reply('Second argument should be either "add" or "sub".');

            return;
        }

        $mode = array_shift($args);

        if (!isset($args[0]) || !ctype_digit($args[0])) {
            $this->reply('Third argument should be an integer.');

            return;
        }

        $amount = array_shift($args);

        $timer = BossTimer::query()
            ->where('name', $name)
            ->firstOrFail();

        $timer->date = $mode === 'sub'
            ? $timer->date->subMinutes($amount)
            : $timer->date->addMinutes($amount);
        $timer->save();

        $this->reply('Boss timer adjusted.');
    }
}
