<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->json('iframes')
                ->after('video_url')
                ->nullable();
        });
    }

    public function down()
    {
        Schema::table('projects', function ($table) {
            $table->dropColumn('iframes');
        });
    }
};
