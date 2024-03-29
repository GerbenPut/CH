<?php

namespace App\Console\Commands;

use App\Models\Attend;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Expression;

class DecayScoresCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chatbot:decay-scores';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        Attend::query()->update([
            'score' => new Expression('score * .8'),
        ]);

        return static::SUCCESS;
    }
}
