<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stories', function (Blueprint $table) {
            $table->string('author')
                ->nullable()
                ->after('cover_image_text');
        });
    }

    public function down()
    {
        Schema::table('stories', function ($table) {
            $table->dropColumn('author');
        });
    }
};
