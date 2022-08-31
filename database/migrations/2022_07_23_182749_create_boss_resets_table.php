<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('boss_resets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boss_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamp('reset_at');
            $table->boolean('adjust');
            $table->timestamps();
        });

        DB::table('boss_resets')->insertUsing(
            ['boss_id', 'reset_at', 'adjust', 'updated_at', 'created_at'],
            DB::table('bosses')
                ->select('id', 'date')
                ->selectRaw('0')
                ->selectRaw('NOW()')
                ->selectRaw('NOW()'),
        );

        Schema::table('bosses', function (Blueprint $table) {
            $table->dropColumn('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('bosses', function (Blueprint $table) {
            $table->datetime('date')->after('type');
        });

        Schema::dropIfExists('boss_resets');
    }
};
