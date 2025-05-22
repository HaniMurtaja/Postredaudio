<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('color_scheme_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->boolean('active')
                ->default(true);
            $table->string('section_name')
                ->nullable();
            $table->string('header_key')
                ->nullable();
            $table->json('content')
                ->nullable();
            $table->integer('resource_id')
                ->nullable();
            $table->string('resource_type')
                ->nullable();
            $table->boolean('scroll_down')
                ->default(false);
            $table->integer('sort_order')
                ->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_blocks');
    }
};
