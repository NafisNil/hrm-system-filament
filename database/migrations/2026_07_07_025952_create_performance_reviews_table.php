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
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->string('review_period');
            $table->integer('quality_of_work')->nullable()->comment('Rating from 1 to 5');
            $table->integer('communication_skills')->nullable()->comment('Rating from 1 to 5');
            $table->integer('teamwork')->nullable()->comment('Rating from 1 to 5');
            $table->integer('problem_solving')->nullable()->comment('Rating from 1 to 5');
            $table->integer('overall_performance')->nullable()->comment('Rating from 1 to 5');
            $table->text('strengths')->nullable();
            $table->text('areas_for_improvement')->nullable();
            $table->text('goals')->nullable();
            $table->text('additional_comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_reviews');
    }
};
