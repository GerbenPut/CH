<?php

namespace App\Support\Commands;

use App\Enums\BossType;
use App\Models\BossSchedule;
use App\Support\Concerns\FormatsDates;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;

class TimersCommand extends Command
{
    use FormatsDates;

    protected string $name = 'timers';

    public function handle(array $args, ?string $group): void
    {
        $advance = isset($args[1]) && ctype_digit($args[1])
            ? intval($args[1])
            : null;

        if (isset($args[0]) && $args[0] == BossType::Raid->value && $group === 'timers') {
            $this->reply('You are not allowed to those bosses yet. *insert evil smiley*');

            return;
        }

        BossSchedule::query()
            ->when(isset($args[0]), fn (Builder $builder) => $builder->where('type', $args[0]))
            ->when($advance !== null, function (Builder $builder) use ($advance) {
                $builder
                    ->where('open', '<=', Date::now()->addMinutes($advance))
                    ->where('closed', '>=', Date::now());
            })
            ->get()
            ->map(function (BossSchedule $schedule) {
                return sprintf(
                    '%s | opens: %s - closes: %s',
                    $schedule->name,
                    $this->date($timer->date->addMinutes($timer->open)),
                    $this->date($timer->date->addMinutes($timer->closed)),
                );
            })
            ->whenNotEmpty(function (Collection $lines) {
                $this->reply($lines->implode("\n"));
            });
    }
}
