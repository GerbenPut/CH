<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('bosses', function (Blueprint $table) {
            $table->unsignedTinyInteger('worth')->after('closed');
            $table->string('points_type')->after('worth');
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
            $table->dropColumn(['points_type', 'worth']);
        });
    }
};
