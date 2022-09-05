<?php

namespace App\Support\Concerns;

use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Date;

trait FormatsDates
{
    protected function date(?CarbonInterface $date): ?string
    {
        if ($date === null) {
            return null;
        }

        $now = Date::now();

        $hourDiff = $now->diffInHours($date, false);

        if ($hourDiff > 0) {
            return $hourDiff . ' hours';
        }

        $minuteDiff = $now->diffInMinutes($date, false);

        return $minuteDiff < 0 ? 'unknown' : $minuteDiff . ' minutes';
    }
}
