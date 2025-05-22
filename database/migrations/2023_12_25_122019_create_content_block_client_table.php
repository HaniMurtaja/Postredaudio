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
        Schema::create('content_block_client', function (Blueprint $table) {
            $table->foreignId('client_id')
                ->constrained('clients')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('content_block_id')
                ->constrained('content_blocks')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->primary(['client_id', 'content_block_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_block_client');
    }
};
