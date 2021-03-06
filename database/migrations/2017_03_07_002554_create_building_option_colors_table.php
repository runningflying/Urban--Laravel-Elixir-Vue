<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuildingOptionColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('building_option_colors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('building_option_id')->unsigned()->index();
            $table->integer('color_id')->unsigned()->index();
            $table->string('custom')->nullable();
            $table->timestamps();

            $table->foreign('color_id')
                  ->references('id')
                  ->on('colors')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('building_option_id')
                  ->references('id')
                  ->on('building_options')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('building_option_colors');
    }
}
