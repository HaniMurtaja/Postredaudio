<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('testimoniables', function (Blueprint $table) {
            $table->integer('sort_order')
                ->after('testimoniable_type')
                ->nullable();
        });
    }

    public function down()
    {
        Schema::table('testimoniables', function ($table) {
            $table->dropColumn('sort_order');
        });
    }
};
