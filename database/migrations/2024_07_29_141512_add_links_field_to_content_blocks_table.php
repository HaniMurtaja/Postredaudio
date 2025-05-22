<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('content_blocks', function (Blueprint $table) {
            $table->json('links')
                ->after('header_key')
                ->nullable();
        });
    }

    public function down()
    {
        Schema::table('content_blocks', function ($table) {
            $table->dropColumn('links');
        });
    }
};
