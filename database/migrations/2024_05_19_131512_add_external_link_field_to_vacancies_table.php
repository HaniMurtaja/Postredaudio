<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vacancies', function (Blueprint $table) {
            $table->string('external_link')
                ->nullable()
                ->after('active');
        });
    }

    public function down()
    {
        Schema::table('vacancies', function ($table) {
            $table->dropColumn('external_link');
        });
    }
};
