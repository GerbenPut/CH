<?php

namespace App\Support\Commands;

use App\Models\Player;
use Illuminate\Support\Collection;
use App\Models\Boss;
use Illuminate\Database\Eloquent\Builder;
use App\Models\PointsPerRun;
use App\Enums\ClassType;
use App\Models\Chat;
use App\Models\BossChat;

class AttendsCommand extends Command
{
    protected string $name = 'attends';

    public function handle(array $args, ?string $group): void
    {
        if ($group === null) {
            return;
        }

        $chat = $group === 'attends_dkp'
            ? Chat::DKP()
            : Chat::QKP();

        if ($args === []) {
            $this->top(10, chat: $chat);

            return;
        }

        $name = $args[0];

        /** @var \App\Models\Boss|null $boss */
        $boss = Boss::query()
            ->where('name', $name)
            ->whereHas('chats', fn (Builder $builder) => $builder->whereKey($chat))
            ->first();

        if ($boss !== null) {
            $classType = count($args) > 1
                ? ClassType::tryFrom($args[1])
                : null;

            $bossChat = BossChat::query()
                ->whereBelongsTo($boss)
                ->whereBelongsTo($chat)
                ->firstOrFail();

            $this->top(10, bossChat: $bossChat, classType: $classType);

            return;
        }

        /** @var \App\Models\Player|null $player */
        $player = Player::query()
            ->where('name', $name)
            ->first();

        if ($player !== null) {
            $bosses = PointsPerRun::query()
                ->with(['bossChat.boss'])
                ->whereBelongsTo($player)
                ->whereHas('bossChat.chat', fn (Builder $builder) => $builder->whereKey($chat))
                ->get()
                ->groupBy('bossChat.boss_id')
                ->map(fn (Collection $pointsPerRuns) => [
                    'boss' => $pointsPerRuns->first()->bossChat->boss,
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

    private function top(int $top, ?BossChat $bossChat = null, ?ClassType $classType = null, ?Chat $chat = null): void
    {
        Player::query()
            ->withCasts([
                'points_per_run_sum_points' => 'float',
            ])
            ->withSum([
                'pointsPerRun' => function (Builder $builder) use ($chat, $bossChat, $classType) {
                    $builder
                        ->when(isset($bossChat), fn (Builder $builder) => $builder->whereBelongsTo($bossChat))
                        ->when(isset($classType), fn (Builder $builder) => $builder->whereRelation('player', 'class_type', $classType))
                        ->when(isset($chat), fn (Builder $builder) => $builder->whereRelation('bossChat', 'chat_id', $chat->id));
                },
            ], 'points')
            ->orderByDesc('points_per_run_sum_points')
            ->limit($top)
            ->get()
            ->map(fn (Player $player) => sprintf('%s: %.2f', $player->name, $player->points_per_run_sum_points))
            ->whenNotEmpty(fn (Collection $lines) => $this->reply($lines->implode("\n")));
    }
}
