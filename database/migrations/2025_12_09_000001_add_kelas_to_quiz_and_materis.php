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
        // Add kelas column to quiz table
        Schema::table('quiz', function (Blueprint $table) {
            if (!Schema::hasColumn('quiz', 'kelas')) {
                $table->string('kelas')->nullable()->after('judul');
            }
        });

        // Add kelas column to materis table (if exists)
        if (Schema::hasTable('materis')) {
            Schema::table('materis', function (Blueprint $table) {
                if (!Schema::hasColumn('materis', 'kelas')) {
                    $table->string('kelas')->nullable()->after('judul');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz', function (Blueprint $table) {
            if (Schema::hasColumn('quiz', 'kelas')) {
                $table->dropColumn('kelas');
            }
        });

        if (Schema::hasTable('materis')) {
            Schema::table('materis', function (Blueprint $table) {
                if (Schema::hasColumn('materis', 'kelas')) {
                    $table->dropColumn('kelas');
                }
            });
        }
    }
};
