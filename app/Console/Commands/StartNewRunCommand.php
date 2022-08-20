<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Run;
use Illuminate\Support\Facades\DB;

class StartNewRunCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chatbot:start-new-run';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        DB::transaction(function () {
            Run::query()->increment('times_decayed');

            Run::query()->create([
                'times_decayed' => 0,
            ]);
        });

        return 0;
    }
}
