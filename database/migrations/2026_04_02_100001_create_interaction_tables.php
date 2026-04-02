<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_interactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->uuid('disease_id')->nullable();
            $table->uuid('recommendation_id')->nullable();
            $table->uuid('doctor_id')->nullable();
            $table->string('interaction_type', 50); // disease_selection, text_input, voice_input
            $table->text('input_text')->nullable();
            $table->string('input_type', 20)->default('text'); // text, voice
            $table->text('ai_response')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('age_category', 30)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamps();

            $table->foreign('disease_id')->references('id')->on('diseases')->nullOnDelete();
            $table->foreign('recommendation_id')->references('id')->on('recommendations')->nullOnDelete();
            $table->foreign('doctor_id')->references('id')->on('doctors')->nullOnDelete();
            $table->index(['created_at']);
            $table->index(['disease_id']);
            $table->index(['age_category']);
        });

        Schema::create('admin_actions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('admin_id')->constrained('users')->cascadeOnDelete();
            $table->string('action_type', 100); // create, update, delete, export
            $table->string('entity_type', 100)->nullable(); // disease, doctor, recommendation, etc.
            $table->uuid('entity_id')->nullable();
            $table->json('action_details')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['admin_id']);
            $table->index(['action_type']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_actions');
        Schema::dropIfExists('user_interactions');
    }
};
