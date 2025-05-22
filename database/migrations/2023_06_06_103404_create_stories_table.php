<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->boolean('active')
                ->default(true);
            $table->boolean('featured')
                ->default(false);
            $table->string('title', 200)
                ->unique();
            $table->string('slug', 200)
                ->unique();
            $table->date('date');
            $table->text('cover_image_text')
                ->nullable();
            $table->text('description')
                ->nullable();
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};
