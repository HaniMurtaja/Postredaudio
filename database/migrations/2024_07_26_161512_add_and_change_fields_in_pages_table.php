<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('slug')
                ->unqiue()
                ->nullable()
                ->change();
            $table->enum('page_type', [0, 1, 2]) // 0 - standard, 1 - special, 2 - external 
                ->after('slug');
            $table->string('external_link')
                ->nullable()
                ->after('active');
            $table->string('menu_label', 100)
                ->nullable()
                ->after('external_link');
            $table->string('label_color', 20)
                ->after('menu_label')
                ->nullable();
            $table->string('label_size', 20)
                ->after('label_color')
                ->nullable();
            $table->string('label_weight', 20)
                ->after('label_size')
                ->nullable();
            $table->string('project_section_label', 100)
                ->after('label_weight')
                ->nullable();
        });
    }

    public function down()
    {
        Schema::table('pages', function ($table) {
            $table->dropColumn('page_type');
            $table->dropColumn('external_link');
            $table->dropColumn('project_section_label');
            $table->string('slug')
                ->unqiue()
                ->change();
            $table->dropColumn('menu_label');
            $table->dropColumn('label_color');
            $table->dropColumn('label_size');
            $table->dropColumn('label_weight');
        });
    }
};
