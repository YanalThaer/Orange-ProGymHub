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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // e.g., 'club_update', 'admin_update'
            $table->morphs('notifiable'); // polymorphic relationship to the entity that owns the notification
            $table->string('title');
            $table->text('message');
            $table->text('data')->nullable(); // JSON data for additional information
            $table->timestamp('read_at')->nullable(); // null = unread, timestamp = read
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
