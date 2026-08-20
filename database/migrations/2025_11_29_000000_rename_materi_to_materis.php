<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('materi') && !Schema::hasTable('materis')) {
            Schema::rename('materi', 'materis');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('materis') && !Schema::hasTable('materi')) {
            Schema::rename('materis', 'materi');
        }
    }
};
