<?php

namespace App\Support\Commands;

use App\Enums\BossType;
use App\Models\BossSchedule;
use App\Support\Concerns\FormatsDates;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;

class DueCommand extends Command
{
    use FormatsDates;

    protected string $name = 'due';

    public function handle(array $args, ?string $group): void
    {
        $advance = isset($args[0]) && ctype_digit($args[0])
            ? intval($args[0])
            : 30;

        BossSchedule::query()
            ->when($group === 'timers', function (Builder $builder) {
                $builder->where('type', '!=', BossType::Raid);
            })
            ->where('open', '<=', Date::now()->addMinutes($advance))
            ->where('closed', '>=', Date::now())
            ->get()
            ->map(function (BossSchedule $schedule) {
                return sprintf(
                    '%s | opens: %s - closes: %s',
                    $schedule->name,
                    $this->date($schedule->open) ?? 'unknown',
                    $this->date($schedule->closed) ?? 'unknown',
                );
            })
            ->whenNotEmpty(function (Collection $lines) {
                $this->reply($lines->implode(', '));
            }, function () {
                $this->reply('No due bosses.');
            });
    }
}
