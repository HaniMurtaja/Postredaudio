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
        Schema::create('testimonial_achievement', function (Blueprint $table) {
            $table->foreignId('testimonial_id')
                ->constrained('testimonials')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('achievement_id')
                ->constrained('achievements')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->primary(['testimonial_id', 'achievement_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonial_achievement');
    }
};
