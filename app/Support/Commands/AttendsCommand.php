<?php

namespace App\Support\Commands;

use App\Models\Player;
use Illuminate\Support\Collection;
use App\Models\Boss;
use Illuminate\Database\Eloquent\Builder;
use App\Models\PointsPerRun;
use App\Enums\ClassType;

class AttendsCommand extends Command
{
    protected string $name = 'attends';

    public function handle(array $args, ?string $group): void
    {
        if ($group !== null) {
            // $pointsType = $group === 'attends_dkp'
            //     ? PointsType::DKP
            //     : PointsType::QKP;

            // Boss::addGlobalScope(new PointTypeScope($pointsType));
        }

        if ($args === []) {
            $this->top(10);

            return;
        }

        $name = $args[0];

        /** @var \App\Models\Boss|null $boss */
        $boss = Boss::query()
            ->where('name', $name)
            ->first();

        if ($boss !== null) {
            $classType = count($args) > 1
                ? ClassType::tryFrom($args[1])
                : null;

            $this->top(10, boss: $boss, classType: $classType);

            return;
        }

        /** @var \App\Models\Player|null $player */
        $player = Player::query()
            ->where('name', $name)
            ->first();

        if ($player !== null) {
            $bosses = PointsPerRun::query()
                ->with(['boss'])
                ->whereHas('boss')
                ->whereBelongsTo($player)
                ->get()
                ->groupBy('boss_id')
                ->map(fn (Collection $pointsPerRuns) => [
                    'boss' => $pointsPerRuns->first()->boss,
                    'points' => $pointsPerRuns->sum('points'),
                ])
                ->sortByDesc('points');


            $bosses
                ->map(fn (array $boss) => sprintf('%s: %s', $boss['boss']->name, $boss['points']))
                ->add('Total: ' . $bosses->sum('points'))
                ->whenNotEmpty(fn (Collection $bosses) => $this->reply($bosses->implode("\n")));

            return;
        }
    }

    private function top(int $top, ?Boss $boss = null, ?ClassType $classType = null): void
    {
        Player::query()
            ->withCasts([
                'points_per_run_sum_points' => 'float',
            ])
            ->withSum([
                'pointsPerRun' => function (Builder $builder) use ($boss, $classType) {
                    $builder
                        ->whereHas('boss')
                        ->when(isset($boss), fn (Builder $builder) => $builder->whereBelongsTo($boss))
                        ->when(isset($classType), fn (Builder $builder) => $builder->whereRelation('player', 'class_type', $classType));
                },
            ], 'points')
            ->orderByDesc('points_per_run_sum_points')
            ->limit($top)
            ->get()
            ->map(fn (Player $player) => sprintf('%s: %.2f', $player->name, $player->points_per_run_sum_points))
            ->whenNotEmpty(fn (Collection $lines) => $this->reply($lines->implode("\n")));
    }
}
