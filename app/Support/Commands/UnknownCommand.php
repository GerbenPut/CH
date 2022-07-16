<?php

namespace App\Support\Commands;

use LINE\LINEBot\Event\MessageEvent\TextMessage;
use App\Models\BossTimer;

class UnknownCommand extends Command
{
    public function handle(TextMessage $event): void
    {
        $pastTimers = BossTimer::query()
            ->where('type', '!=' , 'raid')
            ->get()
            ->filter(function (BossTimer $timer) {
                return $timer->date->addMinutes($timer->open)->isPast()
                     && $timer->date->addMinutes($timer->closed)->isPast();
            })
            ->pluck('name');

        if ($pastTimers->isEmpty()) {
            $this->reply('No past bosses.');

            return;
        }

        $this->reply($pastTimers->implode(', '));
    }
}
