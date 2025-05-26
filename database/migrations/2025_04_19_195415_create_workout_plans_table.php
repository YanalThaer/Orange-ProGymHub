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
        Schema::create('workout_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->nullable()->constrained('clubs')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('coach_id')->nullable()->constrained('coaches')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('category')->nullable();
            $table->integer('duration_weeks')->default(4);
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced'])->default('intermediate');
            $table->boolean('is_template')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_plans');
    }
};
