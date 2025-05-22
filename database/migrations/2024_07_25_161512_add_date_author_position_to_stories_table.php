<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stories', function (Blueprint $table) {
            $table->string('date_author_position')
                ->after('author')
                ->default('left');
        });
    }

    public function down()
    {
        Schema::table('stories', function ($table) {
            $table->dropColumn('date_author_position');
        });
    }
};
