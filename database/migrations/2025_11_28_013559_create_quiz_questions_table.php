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
        // If an older table exists, drop it so migration can recreate with correct constraints
        Schema::dropIfExists('quiz_questions');

        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quiz')->onDelete('cascade');
            $table->text('pertanyaan');
            $table->json('pilihan'); // ['A' => 'jawaban A', 'B' => 'jawaban B', ...]
            $table->string('jawaban_benar'); // 'A', 'B', 'C', atau 'D'
            $table->integer('poin')->default(10);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
