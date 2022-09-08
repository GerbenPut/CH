<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Boss;
use App\Models\Player;
use App\Enums\BossType;
use App\Enums\ClassType;
use App\Models\Run;
use App\Models\Chat;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $bossOne = Boss::query()->create([
            'name' => 'test-boss',
            'type' => BossType::DragonLord,
            'open' => 180,
            'closed' => 190,
        ]);

        $DKP = Chat::query()->create([
            'name' => 'DKP',
        ]);

        $QKP = Chat::query()->create([
            'name' => 'QKP',
        ]);

        $bossOne->chats()->attach($DKP, ['kill_worth' => 10]);
        $bossOne->chats()->attach($QKP, ['kill_worth' => 5]);

        Player::query()->create([
            'name' => 'noel',
            'class_type' => ClassType::Mage,
        ]);
    }
}
