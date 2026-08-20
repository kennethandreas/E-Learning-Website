<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('aktivitas_pembelajaran') && Schema::hasTable('quiz')) {
            Schema::table('aktivitas_pembelajaran', function (Blueprint $table) {
                $table->foreign('quiz_id')->references('id')->on('quiz')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('aktivitas_pembelajaran')) {
            Schema::table('aktivitas_pembelajaran', function (Blueprint $table) {
                $table->dropForeign(['quiz_id']);
            });
        }
    }
};
