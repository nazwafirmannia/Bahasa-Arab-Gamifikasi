<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('user', 'id_user')->onDelete('cascade');
            $table->foreignId('id_quiz')->constrained('quiz', 'id_quiz')->onDelete('cascade');
            $table->integer('score')->default(0); // 0-100
            $table->string('status')->default('pending'); // lulus / gagal
            $table->json('answers')->nullable(); // {1: "A", 2: "Benar", ...}
            $table->integer('time_taken_sec')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};