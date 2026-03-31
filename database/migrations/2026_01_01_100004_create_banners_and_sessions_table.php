<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->nullable()->constrained('clinics')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('media_path')->nullable(); // image or video file path
            $table->string('link')->nullable();
            $table->enum('media_type', ['image', 'video', 'text'])->default('image');
            $table->enum('position', ['sidebar', 'top', 'chat'])->default('sidebar');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });

        Schema::create('web_chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_key', 128)->unique()->index();
            $table->string('gender', 20)->nullable();
            $table->string('age_category', 50)->nullable();
            $table->uuid('disease_id')->nullable();
            $table->text('symptoms')->nullable();
            $table->text('ai_response')->nullable();
            $table->string('input_type', 20)->default('text');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->foreign('disease_id')->references('id')->on('diseases')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('web_chat_sessions');
        Schema::dropIfExists('banners');
    }
};
