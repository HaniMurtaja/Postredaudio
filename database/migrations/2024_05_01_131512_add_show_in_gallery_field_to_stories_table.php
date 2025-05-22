<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stories', function (Blueprint $table) {
            $table->boolean('show_in_gallery')
                ->default(false)
                ->after('featured');
        });
    }

    public function down()
    {
        Schema::table('stories', function ($table) {
            $table->dropColumn('show_in_gallery');
        });
    }
};
