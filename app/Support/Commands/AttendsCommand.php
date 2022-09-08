<?php

namespace App\Support\Commands;

use App\Models\Attend;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\ClassType;
use App\Models\Chat;

class AttendsCommand extends Command
{
    protected string $name = 'attends';

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

        if ($classType === null && isset($args[0])) {
            /** @var \App\Models\Attend $attend */
            $attend = Attend::query()
                ->whereBelongsTo($chat)
                ->whereRelation('player', 'name', $args[0])
                ->first();

            if ($attend !== null) {
                $this->reply($attend->score);
            }
        }

        Attend::query()
            ->with(['player'])
            ->when($classType !== null, fn (Builder $builder) => $builder->whereRelation('player', 'class_type', $classType))
            ->whereBelongsTo($chat)
            ->orderByDesc('score')
            ->limit(10)
            ->get()
            ->map(fn (Attend $attend) => sprintf('%s: %.2f', $attend->player->name, $attend->score))
            ->whenNotEmpty(fn (Collection $lines) => $this->reply($lines->implode("\n")));
    }
}
