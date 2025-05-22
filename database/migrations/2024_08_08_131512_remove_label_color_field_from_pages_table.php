<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('label_color');
        });
    }

    public function down()
    {
        Schema::table('pages', function ($table) {
            $table->string('label_color', 20)
                ->after('menu_label')
                ->nullable();
        });
    }
};
