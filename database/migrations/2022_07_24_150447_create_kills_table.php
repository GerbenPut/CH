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
        Schema::create('kills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('boss_chat_id')->constrained('boss_chat')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('kills');
            $table->unsignedDecimal('score', 14, 4)->default(0);
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
        Schema::dropIfExists('attends');
    }
};
