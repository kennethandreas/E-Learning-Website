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
        // If an older/incorrect table exists, remove it so migration can recreate with correct constraints
        Schema::dropIfExists('aktivitas_pembelajaran');

        Schema::create('aktivitas_pembelajaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('materi_id')->nullable()->constrained('materis')->onDelete('set null');
            // Create quiz_id column without foreign key; constraint will be added after quiz table exists
            $table->unsignedBigInteger('quiz_id')->nullable()->index();
            $table->string('jenis'); // 'materi', 'quiz', dll
            $table->string('status')->default('belum_selesai'); // 'belum_selesai', 'selesai'
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->integer('durasi')->nullable(); // dalam detik
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas_pembelajaran');
    }
};
