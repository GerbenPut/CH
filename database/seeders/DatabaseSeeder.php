<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Boss;
use App\Models\Player;
use App\Enums\BossType;
use App\Enums\ClassType;
use App\Models\Run;
use App\Enums\PointsType;

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
            'type' => BossType::DragonLord,
            'open' => 180,
            'closed' => 190,
            'worth' => 10,
            'camp_worth' => 5,
            'points_type' => PointsType::DKP,
        ]);

        Player::query()->create([
            'name' => 'noel',
            'class_type' => ClassType::Mage,
        ]);

        Run::query()->create([
            'times_decayed' => 0,
        ]);
    }
}
