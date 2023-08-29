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
        Schema::create('achievements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->integer('year')->nullable();
            $table->string('organizer')->nullable();
            $table->string('url')->nullable();
            $table->text('description')->nullable();
            $table->string('level');
            $table->string('grade_level');
            $table->string('certificate_image')->nullable();
            $table->string('certificate_pdf')->nullable();
            $table->boolean('confirmed')->default(0);
            $table->foreignUuid('user_id');
            $table->foreignUuid('subject_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
