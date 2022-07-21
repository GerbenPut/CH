<?php

namespace App\Support\Commands;

use App\Models\BossTimer;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;

class DueCommand extends Command
{
    protected string $name = 'due';

    public function handle(array $args, ?string $group): void
    {
        $advance = isset($args[0]) && ctype_digit($args[0])
            ? intval($args[0])
            : 30;

        $pastTimers = BossTimer::query()
            ->when($group === 'timers', function (Builder $builder) {
                $builder->where('type', '!=' , 'raid');
            })
            ->get()
            ->filter(function (BossTimer $timer) use ($advance) {
                return $timer->date->addMinutes($timer->open)->subMinutes($advance + 1)->lessThanOrEqualTo(now())
                     && $timer->date->addMinutes($timer->closed)->greaterThanOrEqualTo(now());
            })
            ->map(function (BossTimer $timer) {
                return sprintf(
                    '%s | opens: %s - closes: %s',
                    $timer->name,
                    $this->date($timer->date->addMinutes($timer->open)),
                    $this->date($timer->date->addMinutes($timer->closed)),
                );
            });

        if ($pastTimers->isEmpty()) {
            $this->reply('No due bosses.');

            return;
        }

        $this->reply($pastTimers->implode(', '));
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
