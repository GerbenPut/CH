<?php

namespace App\Support\Commands;

use App\Models\Boss;
use Illuminate\Support\Facades\Date;

class AdjustCommand extends Command
{
    protected string $name = 'adjust';

    public function handle(array $args): void
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

        /** @var \App\Models\Boss $boss */
        $boss = Boss::query()
            ->byName($name)
            ->firstOrFail();

        $resetAt = $boss->latestReset->reset_at ?? Date::now();

        $boss->latestReset()->create([
            'adjust' => true,
            'reset_at' => $mode === 'sub'
                ? $resetAt->subMinutes($amount)
                : $resetAt->addMinutes($amount)
        ]);

        $this->reply('Boss timer adjusted.');
    }
}
