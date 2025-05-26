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
        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password');
            $table->string('bio')->nullable();
            $table->string('location')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Jordan');
            $table->text('description')->nullable();
            $table->integer('capacity')->nullable();
            $table->boolean('has_parking')->default(false);
            $table->boolean('has_wifi')->default(false);
            $table->boolean('has_showers')->default(false);
            $table->boolean('has_lockers')->default(false);
            $table->boolean('has_pool')->default(false);
            $table->boolean('has_sauna')->default(false);
            $table->string('website')->nullable();
            $table->json('social_media')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->enum('status', ['active', 'inactive', 'under_maintenance'])->default('active');
            $table->date('established_date')->nullable();
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
            $table->string('logo')->nullable();
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->json('working_days')->nullable();
            $table->json('special_hours')->nullable();
            $table->rememberToken()->nullable();

            $table->string('verification_code')->nullable();
            $table->timestamp('verification_code_expires_at')->nullable();
            $table->boolean('email_verified')->default(false);
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
        Schema::dropIfExists('clubs');
    }
};
