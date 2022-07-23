<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Boss;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        Boss::query()->create([
            'name' => 'test-boss',
            'type' => 'normal',
            'date' => now(),
            'open' => 180,
            'closed' => 190,
        ]);
    }
}
