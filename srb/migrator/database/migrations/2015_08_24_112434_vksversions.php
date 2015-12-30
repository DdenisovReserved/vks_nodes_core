<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VksVersions extends Migration
{
    public function up()
    {
        Schema::create('vks_versions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("vks_id");
            $table->integer("version")->default(1);
            $table->longText("dump");
            $table->unsignedInteger("changed_by");
            $table->timestamps();
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
        Schema::drop('vks_versions');
    }
}
