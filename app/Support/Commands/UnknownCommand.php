<?php

namespace App\Support\Commands;

use App\Enums\BossType;
use App\Models\BossSchedule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Date;

class UnknownCommand extends Command
{
    protected string $name = 'unknown';

    public function handle(): void
    {
        $pastTimers = BossSchedule::query()
            ->where('type', '!=', BossType::Raid)
            ->where(function (Builder $builder) {
                $builder
                    ->whereNull('reset_at')
                    ->orWhere(function (Builder $builder) {
                        $builder
                            ->where('open', '<', Date::now())
                            ->where('closed', '<', Date::now());
                    });
            })
            ->pluck('name');

        if ($pastTimers->isEmpty()) {
            $this->reply('No past bosses.');

            return;
        }

        $this->reply($pastTimers->implode(', '));
    }
}
