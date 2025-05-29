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
        Schema::create('coaches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('location')->nullable();
            $table->string('password');
            $table->text('bio')->nullable();
            $table->foreignId('club_id')->nullable()->constrained('clubs')->onDelete('cascade');

            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('profile_image')->nullable();
            $table->integer('experience_years')->nullable();
            $table->json('certifications')->nullable(); // Professional certifications
            $table->json('specializations')->nullable(); // Fitness specializations

            $table->string('employment_type')->nullable(); // Full-time, part-time, contractor
            $table->json('working_hours')->nullable(); // أوقات الدوام للمدرب

            $table->string('verification_code')->nullable();
            $table->timestamp('email_verified_at')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coaches');
    }
};
