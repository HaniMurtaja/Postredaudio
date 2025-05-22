<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->integer('sort_order')->after('id');
        });

        DB::statement('UPDATE categories SET sort_order = id');
    }

    public function down()
    {
        Schema::table('categories', function ($table) {
            $table->dropColumn('sort_order');
        });
    }
};
