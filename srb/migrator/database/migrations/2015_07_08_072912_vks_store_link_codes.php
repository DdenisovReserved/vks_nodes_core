<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VksStoreLinkCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vks_store_link_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vks_id');
            $table->string('value');
            $table->string('tip')->nullable();
            /*
            $table->foreign('vks_id')->references('id')->on('vks_store')->onUpdate('cascade')->onDelete('cascade');
            */

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vks_store_link_codes');
    }
}
