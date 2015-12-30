<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->default('Не указано');
            $table->string('ip', 255)->default('Не указано');
            $table->integer('check')->default(1);
            $table->unsignedInteger('parent_id')->default(1)->nullable();
            $table->integer('container')->default(0);
            $table->integer('active')->default(1);


            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('attendance')
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
        Schema::drop('attendance');
    }
}
