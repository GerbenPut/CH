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
        Schema::create('boss_timers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->datetime('date');
            $table->unsignedInteger('open');
            $table->unsignedInteger('closed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('boss_timers');
    }
};
