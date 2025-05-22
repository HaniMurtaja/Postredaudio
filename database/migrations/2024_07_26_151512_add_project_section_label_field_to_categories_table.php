<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('project_section_label', 100)
                ->after('slug')
                ->nullable();
        });
    }

    public function down()
    {
        Schema::table('categories', function ($table) {
            $table->dropColumn('project_section_label');
        });
    }
};
