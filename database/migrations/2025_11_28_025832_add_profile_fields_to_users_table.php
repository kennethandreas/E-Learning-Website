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
        Schema::table('users', function (Blueprint $table) {
            $table->date('tanggal_lahir')->nullable()->after('kelas');
            $table->text('tentang_aku')->nullable()->after('tanggal_lahir');
            $table->string('email_orang_tua')->nullable()->after('tentang_aku');
            $table->string('nomor_telepon_orang_tua')->nullable()->after('email_orang_tua');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tanggal_lahir', 'tentang_aku', 'email_orang_tua', 'nomor_telepon_orang_tua']);
        });
    }
};
