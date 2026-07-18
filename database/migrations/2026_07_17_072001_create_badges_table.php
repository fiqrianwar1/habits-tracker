<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->text('icon_svg');
            $table->string('condition'); // e.g., 'first_activity', 'marathon'
            $table->timestamps();
        });

        // Insert Default Badges
        DB::table('badges')->insert([
            [
                'name' => 'First Blood',
                'description' => 'Berhasil menyelesaikan aktivitas pertama!',
                'condition' => 'first_activity',
                'icon_svg' => '<svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
            ],
            [
                'name' => 'Marathon',
                'description' => 'Fokus tingkat dewa! Melakukan aktivitas lebih dari 3 jam nonstop.',
                'condition' => 'marathon',
                'icon_svg' => '<svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>',
            ],
            [
                'name' => 'Consistent 7',
                'description' => 'Tidak pernah bolong selama 7 hari berturut-turut.',
                'condition' => 'consistent_7',
                'icon_svg' => '<svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
            ]
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
