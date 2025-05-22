<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->boolean('active')
                ->default(true)
                ->after('slug');
        });
    }

    public function down()
    {
        Schema::table('pages', function ($table) {
            $table->dropColumn('active');
        });
    }
};
