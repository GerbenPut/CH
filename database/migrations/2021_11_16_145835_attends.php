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
        Schema::create('attends', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('lower');
            $table->integer('camped');
            $table->integer('215');
            $table->integer('prot');
            $table->integer('gele');
            $table->integer('bt');
            $table->integer('dino');
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
