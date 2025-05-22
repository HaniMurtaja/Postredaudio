<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimoniables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('testimonial_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('testimoniable_id');
            $table->string('testimoniable_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimoniables');
    }
};
