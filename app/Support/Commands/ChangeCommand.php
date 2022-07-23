<?php

namespace App\Support\Commands;

use App\Models\Boss;

class ChangeCommand extends Command
{
    protected string $name = 'change';

    public function handle(array $args): void
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

        /** @var \App\Models\Boss $boss */
        $boss = Boss::query()
            ->where('name', $name)
            ->firstOrFail();

        $boss->open = intval($args[0]);
        $boss->closed = intval($args[1]);
        $boss->save();

        $this->reply('Boss timer changed.');
    }
}
