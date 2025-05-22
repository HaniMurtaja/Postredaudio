<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_gallery_items', function (Blueprint $table) {
            $table->id();
            $table->integer('resource_id');
            $table->string('resource_type');
            $table->string('title');
            $table->integer('sort_order')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_gallery_items');
    }
};
