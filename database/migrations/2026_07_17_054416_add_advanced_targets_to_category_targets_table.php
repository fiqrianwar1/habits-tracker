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
            $table->json('target_days_of_week')->nullable();
            $table->decimal('minimum_hours_per_day', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_targets', function (Blueprint $table) {
            $table->dropColumn(['target_days_of_week', 'minimum_hours_per_day']);
        });
    }
};
