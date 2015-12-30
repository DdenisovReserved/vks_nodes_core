<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OutlookCalendarRequestsCreateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outlook_calendar_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vks_id');
            $table->integer('user_id');
            $table->integer('request_type')->default(0); //init status - new calendar request
            $table->integer('send_status')->default(0); //init status - need to be sending
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('outlook_calendar_requests');
    }
}
