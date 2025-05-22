<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->json('display_name')
                ->after('name')
                ->nullable();
        });
    }

    public function down()
    {
        Schema::table('departments', function ($table) {
            $table->dropColumn('display_name');
        });
    }
};
