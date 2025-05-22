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
        Schema::create('project_cast', function (Blueprint $table) {
            $table->foreignId('project_id')
                ->constrained('projects')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('cast_id')
                ->constrained('cast')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('sort_order')
                ->nullable();
            $table->primary(['project_id', 'cast_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_cast');
    }
};
