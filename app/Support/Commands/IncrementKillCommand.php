<?php

namespace App\Support\Commands;

use App\Models\Attend;
use App\Models\Kill;
use App\Models\Boss;
use App\Models\Player;
use App\Models\Chat;
use Illuminate\Database\Eloquent\Builder;
use App\Models\BossChat;
use Illuminate\Support\Facades\DB;

class IncrementKillCommand extends Command
{
    protected string $name = '*';

    public function handle(array $args, string $command, ?string $group): void
    {
        if ($group === null) {
            return;
        }

        $chat = $group === 'attends_dkp'
            ? Chat::DKP()
            : Chat::QKP();

        if (count($args) < 1) {
            $this->reply('Please specify a boss.');

            return;
        }

        /** @var \App\Models\Player $player */
        $player = Player::query()
            ->where('name', $command)
            ->firstOrFail();

        /** @var \App\Models\Boss $boss */
        $boss = Boss::query()
            ->byName(array_shift($args))
            ->whereHas('chats', fn (Builder $builder) => $builder->whereKey($chat))
            ->firstOrFail();

        /** @var \App\Models\BossChat $bossChat */
        $bossChat = BossChat::query()
            ->whereBelongsTo($boss)
            ->whereBelongsTo($chat)
            ->firstOrFail();

        /** @var \App\Models\Kill $kill */
        $kill = $player->kills()
            ->whereBelongsTo($bossChat)
            ->firstOrNew();

        $kill->kills ??= 0;
        $kill->kills++;

        $kill->bossChat()->associate($bossChat);

        /** @var \App\Models\Attend $attend */
        $attend = Attend::query()
            ->whereBelongsTo($chat)
            ->whereBelongsTo($player)
            ->firstOrNew();

        $attend->chat()->associate($chat);
        $attend->player()->associate($player);
        $attend->score ??= 0;
        $attend->score += $bossChat->kill_worth;

        DB::transaction(function () use ($attend, $kill) {
            $attend->save();
            $kill->save();
        });

        $this->reply('Kills incremented');
    }
}
