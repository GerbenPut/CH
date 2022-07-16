<?php

namespace App\Support\Commands;

use LINE\LINEBot\Event\MessageEvent\TextMessage;
use App\Models\BossTimer;
use Illuminate\Database\Eloquent\Builder;
use Carbon\CarbonInterface;

class TimersCommand extends Command
{
    public function handle(TextMessage $event, array $args): void
    {
        if (isset($args[0]) && $args[0] == "raid") {
            $this->reply('You are not allowed to those bosses yet. *insert evil smiley*');

            return;
        }

        $lines = BossTimer::query()
            ->when(isset($args[0]), fn (Builder $builder) => $builder->where('type', $args[0]))
            ->get()
            ->map(function (BossTimer $timer) {
                return sprintf(
                    '%s | opens: %s - closes: %s',
                    $timer->name,
                    $this->date($timer->date->addMinutes($timer->open)),
                    $this->date($timer->date->addMinutes($timer->closed)),
                );
            });

        if ($lines->isEmpty()) {
            $this->reply('Invalid timer type. See notes for options.');

            return;
        }

        $this->reply($lines->implode("\n"));
    }

    private function date(CarbonInterface $date): string
    {
        $now = now();

        $hourDiff = $now->diffInHours($date, false);

        if ($hourDiff > 0) {
            return $hourDiff . ' hours';
        }

        $minuteDiff = $now->diffInMinutes($date, false);

        return $minuteDiff < 0 ? 'unknown' : $minuteDiff . ' minutes';
    }
}
