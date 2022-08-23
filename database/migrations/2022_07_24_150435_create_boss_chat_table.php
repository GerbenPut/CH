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
        Schema::create('boss_chat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boss_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('chat_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedTinyInteger('kill_worth');
            $table->unsignedTinyInteger('camp_worth');
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
        Schema::dropIfExists('boss_chat');
    }
};
