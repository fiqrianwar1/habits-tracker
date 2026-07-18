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
        Schema::table('category_targets', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'category']);
            $table->integer('month')->default(date('n'));
            $table->integer('year')->default(date('Y'));
            $table->unique(['user_id', 'category', 'month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_targets', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'category', 'month', 'year']);
            $table->dropColumn(['month', 'year']);
            $table->unique(['user_id', 'category']);
        });
    }
};
