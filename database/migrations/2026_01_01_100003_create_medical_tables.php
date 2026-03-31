<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diseases', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('clinic_id')->nullable()->constrained('clinics')->nullOnDelete();
            $table->string('name', 500);
            $table->string('category', 255)->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('min_age')->default(0);
            $table->unsignedInteger('max_age')->default(150);
            $table->boolean('is_active')->default(true);
            $table->integer('sheets_row_id')->nullable();
            $table->timestamps();
        });

        Schema::create('doctors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('clinic_id')->nullable()->constrained('clinics')->nullOnDelete();
            $table->string('name', 500);
            $table->string('phone_number', 50)->nullable();
            $table->text('location_url')->nullable();
            $table->string('specialization', 255)->nullable();
            $table->text('photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sheets_row_id')->nullable();
            $table->timestamps();
        });

        Schema::create('recommendations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('disease_id');
            $table->uuid('doctor_id')->nullable();
            $table->text('recommendation_text');
            $table->integer('priority')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('sheets_row_id')->nullable();
            $table->timestamps();

            $table->foreign('disease_id')->references('id')->on('diseases')->cascadeOnDelete();
            $table->foreign('doctor_id')->references('id')->on('doctors')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendations');
        Schema::dropIfExists('doctors');
        Schema::dropIfExists('diseases');
    }
};
