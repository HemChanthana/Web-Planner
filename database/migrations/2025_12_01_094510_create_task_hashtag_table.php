<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('task_hashtag', function (Blueprint $table) {
        $table->id();

        $table->foreignId('task_id')
              ->constrained('tasks')
              ->cascadeOnDelete();

        $table->foreignId('hashtag_id')
              ->constrained('hashtags')
              ->cascadeOnDelete();

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('task_hashtag');
}
};
