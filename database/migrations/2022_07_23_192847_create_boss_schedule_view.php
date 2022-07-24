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
CREATE VIEW boss_schedule AS
SELECT
    bosses.id,
    bosses.name,
    bosses.type,
    boss_resets.reset_at,
    date_add(boss_resets.reset_at, INTERVAL bosses.open MINUTE) AS open,
    date_add(boss_resets.reset_at, INTERVAL bosses.closed MINUTE) AS closed
FROM
    bosses
    LEFT JOIN boss_resets ON boss_resets.boss_id = bosses.id
        AND boss_resets.id = (
            SELECT
                id
            FROM
                boss_resets
        WHERE
            boss_id = bosses.id
        ORDER BY
            created_at DESC
        LIMIT 1);
SQL);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS `boss_schedule`;');
    }
};
