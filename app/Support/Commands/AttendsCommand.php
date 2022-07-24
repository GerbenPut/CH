<?php

namespace App\Support\Commands;

use App\Models\Attend;
use App\Models\Player;
use Illuminate\Support\Collection;

class AttendsCommand extends Command
{
    protected string $name = 'attends';

    public function handle(array $args): void
    {
        if (!isset($args[0])) {
            $this->reply('Please specify a name.');

            return;
        }

        $player = Player::query()
            ->where('name', $args[0])
            ->firstOrFail();

        Attend::query()
            ->with(['boss'])
            ->whereBelongsTo($player)
            ->orderByDesc('kills')
            ->get()
            ->map(fn (Attend $attend) => sprintf('%s: %d', $attend->boss->name, $attend->kills))
            ->whenNotEmpty(function (Collection $lines) {
                $this->reply($lines->implode("\n"));
            });
    }
}
