<?php

namespace App\Support\Commands;

use App\Models\Boss;
use App\Models\Player;
use App\Models\Run;
use App\Models\Chat;
use Illuminate\Database\Eloquent\Builder;
use App\Models\BossChat;

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

        $boss = array_shift($args);

        if ($boss === 'camped' && count($args) >= 2) {
            $boss = $args[0];
            $hours = intval($args[1]);
        }

        /** @var \App\Models\Run $run */
        $run = Run::query()
            ->latest()
            ->firstOrFail();

        /** @var \App\Models\Player $player */
        $player = Player::query()
            ->where('name', $command)
            ->firstOrFail();

        /** @var \App\Models\Boss $boss */
        $boss = Boss::query()
            ->where('name', $boss)
            ->whereHas('chats', fn (Builder $builder) => $builder->whereKey($chat))
            ->firstOrFail();

        $bossChat = BossChat::query()
            ->whereBelongsTo($boss)
            ->whereBelongsTo($chat)
            ->firstOrFail();

        /** @var \App\Models\Attend $attend */
        $attend = $player->attends()
            ->whereBelongsTo($bossChat)
            ->whereBelongsTo($run)
            ->firstOrNew();

        $attend->camps ??= 0;
        $attend->kills ??= 0;

        if (isset($hours)) {
            $attend->camps += $hours;
        } else {
            $attend->kills++;
        }

        $attend->bossChat()->associate($bossChat);
        $attend->run()->associate($run);
        $attend->save();

        if (isset($hours)) {
            $this->reply('Camps incremented');
        } else {
            $this->reply('Kills incremented');
        }
    }
}
