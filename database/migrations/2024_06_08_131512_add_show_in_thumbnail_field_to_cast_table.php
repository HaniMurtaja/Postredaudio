<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cast', function (Blueprint $table) {
            $table->boolean('show_in_thumbnail')
                ->default(false)
                ->after('show_in_list');
        });
    }

    public function down()
    {
        Schema::table('cast', function ($table) {
            $table->dropColumn('show_in_thumbnail');
        });
    }
};
