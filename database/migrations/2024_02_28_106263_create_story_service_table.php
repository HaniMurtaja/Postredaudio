<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('story_service', function (Blueprint $table) {
            $table->foreignId('story_id')
                ->constrained('stories')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('service_id')
                ->constrained('categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->primary(['story_id', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('story_service');
    }
};
