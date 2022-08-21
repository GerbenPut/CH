<?php

namespace App\Support\Commands;

use App\Models\Attend;
use App\Models\Player;
use App\Models\Boss;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use App\Enums\PointsType;
use App\Models\Scopes\PointTypeScope;

class KillsCommand extends Command
{
    protected string $name = 'kills';

    public function handle(array $args, ?string $group): void
    {
        if ($group !== null) {
            $pointsType = $group === 'attends_dkp'
                ? PointsType::DKP
                : PointsType::QKP;

            Boss::addGlobalScope(new PointTypeScope($pointsType));
        }

        if (count($args) < 1) {
            $this->reply('Please specify a player and optional boss name.');

            return;
        }

        $player = Player::query()
            ->where('name', $args[0])
            ->firstOrFail();

        if (isset($args[1])) {
            $boss = Boss::query()
                ->where('name', $args[1])
                ->firstOrFail();
        }

        $attends = Attend::query()
            ->when(
                isset($boss),
                fn (Builder $builder) => $builder->whereBelongsTo($boss),
                fn (Builder $builder) => $builder->whereHas('boss'),
            )
            ->whereBelongsTo($player)
            ->get();

        if (isset($boss)) {
            $this->reply($attends->sum('kills'));

            return;
        }

        $attends->groupBy('boss_id')
            ->map(fn (EloquentCollection $attends) => [
                'boss' => $attends->first()->boss,
                'kills' => $attends->sum('kills'),
            ])
            ->map(fn (array $attend) => sprintf('%s: %d', $attend['boss']->name, $attend['kills']))
            ->whenNotEmpty(function (Collection $lines) {
                $this->reply($lines->implode("\n"));
            });
    }
}
