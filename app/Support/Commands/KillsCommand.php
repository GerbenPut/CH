<?php

namespace App\Support\Commands;

use App\Enums\ClassType;
use App\Models\Kill;
use App\Models\Player;
use App\Models\Boss;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use App\Models\Chat;

class KillsCommand extends Command
{
    protected string $name = 'kills';

    public function handle(array $args, ?string $group): void
    {
        if ($group === null) {
            return;
        }

        $chat = $group === 'attends_dkp'
            ? Chat::DKP()
            : Chat::QKP();

        $classType = isset($args[0])
            ? ClassType::tryFrom($args[0])
            : null;

        if (count($args) < 1 || $classType !== null) {
            Kill::query()
                ->with(['player'])
                ->select('player_id')
                ->selectRaw('SUM(kills) as kills')
                ->when($classType !== null, function (Builder $builder) use ($classType) {
                    return $builder->whereRelation('player', 'class_type', $classType);
                })
                ->whereRelation('bossChat', 'chat_id', $chat->id)
                ->orderByDesc('kills')
                ->groupBy('player_id')
                ->limit(10)
                ->get()
                ->map(fn (Kill $kill) => sprintf('%s: %d', $kill->player->name, $kill->kills))
                ->whenNotEmpty(fn (Collection $lines) => $this->reply($lines->implode("\n")));

            return;
        }

        /** @var \App\Models\Player|null $player */
        $player = Player::query()
            ->where('name', $args[0])
            ->first();

        if ($player !== null) {
            Kill::query()
                ->with(['bossChat.boss'])
                ->select('boss_chat_id')
                ->selectRaw('SUM(kills) as kills')
                ->leftJoin('boss_chat', 'kills.boss_chat_id', 'boss_chat.id')
                ->whereBelongsTo($player)
                ->where('boss_chat.chat_id', $chat->id)
                ->groupBy('kills.boss_chat_id')
                ->orderByDesc('kills')
                ->get()
                ->map(fn (Kill $kill) => sprintf('%s: %d', $kill->bossChat->boss->name, $kill->kills))
                ->whenNotEmpty(fn (Collection $lines) => $this->reply($lines->implode("\n")));

            return;
        }

        /** @var \App\Models\Boss|null $boss */
        $boss = Boss::query()
            ->byName($args[0])
            ->first();

        if ($boss !== null) {
            $classType = isset($args[1])
                ? ClassType::tryFrom($args[1])
                : null;

            Kill::query()
                ->with(['player'])
                ->select('player_id')
                ->selectRaw('SUM(kills) as kills')
                ->whereRelation('bossChat', [
                    'boss_id' => $boss->id,
                    'chat_id' => $chat->id,
                ])
                ->when($classType !== null, function (Builder $builder) use ($classType) {
                    $builder->whereRelation('player', 'class_type', $classType);
                })
                ->orderByDesc('kills')
                ->groupBy('player_id')
                ->limit(10)
                ->get()
                ->map(fn (Kill $kill) => sprintf('%s: %d', $kill->player->name, $kill->kills))
                ->whenNotEmpty(fn (Collection $lines) => $this->reply($lines->implode("\n")));

            return;
        }

        $this->reply('Unknown kill request');
    }
}
