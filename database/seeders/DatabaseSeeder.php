<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BossTimer;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        BossTimer::query()->create([
            'name' => 'test-boss',
            'type' => 'normal',
            'date' => now(),
            'open' => 180,
            'closed' => 190,
        ]);
    }
}
