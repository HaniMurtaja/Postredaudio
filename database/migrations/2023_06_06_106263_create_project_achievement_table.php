<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_achievement', function (Blueprint $table) {
            $table->foreignId('project_id')
                ->constrained('projects')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('achievement_id')
                ->constrained('achievements')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->primary(['project_id', 'achievement_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_achievement');
    }
};
