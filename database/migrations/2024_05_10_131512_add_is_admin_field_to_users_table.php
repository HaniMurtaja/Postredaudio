<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')
                ->default(false)
                ->after('remember_token');
        });
    }

    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('is_admin');
        });
    }
};
