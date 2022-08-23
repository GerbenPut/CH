<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::statement(<<<SQL
CREATE VIEW points_per_run AS
SELECT
	player_id,
	boss_id,
	run_id,
	SUM(kills * boss_chat.kill_worth + camps * boss_chat.camp_worth) * POWER(.8, runs.times_decayed) AS points
FROM
	attends
	JOIN boss_chat ON boss_chat.id = attends.boss_chat_id
	JOIN runs ON runs.id = attends.run_id
GROUP BY
	player_id,
	run_id,
	boss_id;
SQL);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS `points_per_run`;');
    }
};
