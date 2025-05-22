<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200)
                ->unique();
            $table->string('slug', 200)
                ->unique();
            $table->text('caption')
                ->nullable();
            $table->text('description')
                ->nullable();
            $table->boolean('active')
                ->default(true);
            $table->boolean('pinned')
                ->default(false);
            $table->boolean('featured')
                ->default(false);
            $table->foreignId('client_id')
                ->nullable()
                ->constrained()
                ->onUpdate('cascade')
                ->nullOnDelete();
            $table->foreignId('industry_id')
                ->nullable()
                ->constrained('categories')
                ->onUpdate('cascade')
                ->nullOnDelete();
            $table->string('video_url')
                ->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
