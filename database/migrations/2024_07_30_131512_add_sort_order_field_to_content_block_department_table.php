<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('content_block_department', function (Blueprint $table) {
            $table->integer('sort_order')->after('content_block_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('content_block_department', function ($table) {
            $table->dropColumn('sort_order');
        });
    }
};
