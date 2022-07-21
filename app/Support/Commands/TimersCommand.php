<?php

namespace App\Support\Commands;

use App\Models\BossTimer;
use Illuminate\Database\Eloquent\Builder;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class TimersCommand extends Command
{
    protected string $name = 'timers';

    public function handle(array $args, ?string $group): void
    {
        $advance = isset($args[1]) && ctype_digit($args[1])
            ? intval($args[1])
            : null;

        if (isset($args[0]) && $args[0] == 'raid' && $group === 'timers') {
            $this->reply('You are not allowed to those bosses yet. *insert evil smiley*');

            return;
        }

        $lines = BossTimer::query()
            ->when(isset($args[0]), fn (Builder $builder) => $builder->where('type', $args[0]))
            ->get()
            ->when($advance !== null, function (EloquentCollection $timers) use ($advance) {
                return $timers
                    ->filter(function (BossTimer $timer) use ($advance) {
                        return $timer->date->addMinutes($timer->open)->subMinutes($advance + 1)->lessThanOrEqualTo(now())
                            && $timer->date->addMinutes($timer->closed)->greaterThanOrEqualTo(now());
                    });
            })
            ->map(function (BossTimer $timer) {
                return sprintf(
                    '%s | opens: %s - closes: %s',
                    $timer->name,
                    $this->date($timer->date->addMinutes($timer->open)),
                    $this->date($timer->date->addMinutes($timer->closed)),
                );
            })
            ->whenNotEmpty(function (Collection $lines) {
                $this->reply($lines->implode("\n"));
            });
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
