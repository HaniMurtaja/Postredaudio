<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projectables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('projectable_id');
            $table->string('projectable_type');
            $table->integer('sort_order')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projectables');
    }
};
