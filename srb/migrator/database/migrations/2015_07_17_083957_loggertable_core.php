<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LoggertableCore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log', function (Blueprint $table) {
            $table->engine = "MyISAM";
            $table->increments('id');
            $table->integer('event_type')->default(23); //other events, see Logger Class
            $table->string('from_ip', 30)->default('127.0.0.0');
            $table->unsignedInteger('by_user')->default(1);
            $table->longText('content')->nullable();
            $table->timestamps();
            /*
            $table->foreign('by_user')->references('id')->on('users')->onDelete('no action')->onUpdate('no action');
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
        Schema::drop('log');
    }
}
