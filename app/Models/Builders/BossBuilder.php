<?php

namespace App\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class BossBuilder extends Builder
{
    public function byName(string $name): static
    {
        return $this->where(function (self $builder) use ($name) {
            $builder
                ->where('name', $name)
                ->orWhereRelation('aliases', 'name', $name);
        });
    }
}
