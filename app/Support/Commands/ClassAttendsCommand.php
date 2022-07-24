<?php

namespace App\Support\Commands;

use App\Enums\ClassType;
use App\Models\Boss;
use App\Models\Player;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ClassAttendsCommand extends Command
{
    protected string $name = '*attends';

    public function handle(array $args, string $command): void
    {
        preg_match('/^(.+)attends$/', $command, $matches);

        if (!isset($matches[1])) {
            $this->reply('Please specify a name and class type respectively.');

            return;
        }

        $classType = ClassType::tryFrom($matches[1]);

        if ($classType === null) {
            $this->reply('Unknown class type');

            return;
        }

        $boss = null;

        if (isset($args[0])) {
            $boss = Boss::query()
                ->where('name', $args[0])
                ->firstOrFail();
        }

        Player::query()
            ->withSum([
                'killCounts as kills' => function (Builder $builder) use ($boss) {
                    $builder->when($boss !== null, fn (Builder $builder) => $builder->whereBelongsTo($boss));
                },
            ], 'kills')
            ->where('class_type', $classType)
            ->orderByDesc('kills')
            ->toBase()
            ->get()
            ->map(fn (object $playerKills) => sprintf('%s: %d', $playerKills->name, $playerKills->kills))
            ->whenNotEmpty(function (Collection $lines) {
                $this->reply($lines->implode("\n"));
            });
    }
}
