<?php

namespace App\Support\Commands;

use App\Enums\BossType;
use App\Models\Boss;
use Illuminate\Support\Facades\Date;

class ResetCommand extends Command
{
    protected string $name = 'reset';

    public function handle(array $args, ?string $group): void
    {
        if (count($args) === 0) {
            $this->reply('Please specify a boss.');

            return;
        }

        /** @var \App\Models\Boss $boss */
        $boss = Boss::query()
            ->where('name', $args[0])
            ->firstOrFail();

        if ($group === 'timers' && $boss->type === BossType::Raid) {
            $this->reply('You are not allowed to those bosses yet. *insert evil smiley*');

            return;
        }

        $boss->latestReset()->create([
            'adjust' => false,
            'reset_at' => Date::now(),
        ]);

        $this->reply($boss->name . ' has been reset.');
    }
}
