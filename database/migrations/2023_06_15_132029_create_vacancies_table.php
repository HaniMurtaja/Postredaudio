<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 250)
                ->unique();
            $table->boolean('active');
            $table->text('about')
                ->nullable();
            $table->text('description');
            $table->json('responsibilities')
                ->nullable();
            $table->json('requirements')
                ->nullable();
            $table->json('skills')
                ->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacancies');
    }
};
