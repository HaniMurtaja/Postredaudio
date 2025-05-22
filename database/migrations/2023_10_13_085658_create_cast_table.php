<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cast', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('position', 200);
            $table->boolean('key_role')->default(false);
            $table->boolean('show_in_list')->default(true);
            $table->foreignId('project_id')
                ->nullable()
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('sort_order')
                ->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cast');
    }
};
