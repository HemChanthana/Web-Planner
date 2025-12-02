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
    Schema::table('task_hashtag', function (Blueprint $table) {
        if (!Schema::hasColumn('task_hashtag', 'task_id')) {
            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
        }

        if (!Schema::hasColumn('task_hashtag', 'hashtag_id')) {
            $table->foreignId('hashtag_id')->constrained('hashtags')->cascadeOnDelete();
        }
    });
}

public function down(): void
{
    Schema::table('task_hashtag', function (Blueprint $table) {
        $table->dropForeign(['task_id']);
        $table->dropForeign(['hashtag_id']);
        $table->dropColumn(['task_id', 'hashtag_id']);
    });
}

};
