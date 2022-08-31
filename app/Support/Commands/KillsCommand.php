<?php

namespace App\Support\Commands;

use App\Models\Attend;
use App\Models\Player;
use App\Models\Boss;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use App\Models\Chat;
use App\Models\BossChat;

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
                ->whereHas('chats', fn (Builder $builder) => $builder->whereKey($chat))
                ->firstOrFail();

            $bossChat = BossChat::query()
                ->whereBelongsTo($boss)
                ->whereBelongsTo($chat)
                ->firstOrFail();
        }

        $attends = Attend::query()
            ->when(
                isset($bossChat),
                fn (Builder $builder) => $builder->whereBelongsTo($bossChat),
                fn (Builder $builder) => $builder->whereRelation('bossChat', 'chat_id', $chat->id),
            )
            ->whereBelongsTo($player)
            ->get();

        if (isset($bossChat)) {
            $this->reply($attends->sum('kills'));

            return;
        }

        $attends
            ->groupBy('bossChat.boss_id')
            ->map(fn (EloquentCollection $attends) => [
                'boss' => $attends->first()->bossChat->boss,
                'kills' => $attends->sum('kills'),
            ])
            ->map(fn (array $attend) => sprintf('%s: %d', $attend['boss']->name, $attend['kills']))
            ->whenNotEmpty(function (Collection $lines) {
                $this->reply($lines->implode("\n"));
            });
    }
}
