<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_module', function (Blueprint $table) {
            $table->foreignId('project_id')
                ->constrained('projects')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('module_id')
                ->constrained('modules')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('sort_order')
                ->nullable();
            $table->primary(['project_id', 'module_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_module');
    }
};
