<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TechSupportRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tech_support_requests', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('vks_id');
            $table->unsignedInteger('att_id');
            $table->unsignedInteger('owner_id');
            $table->string('user_message', 255)->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();

//            $table->foreign('tech_support_mails_id')->references('id')
//                ->on('tech_support_core_mails')
//                ->onUpdate('cascade')
//                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tech_support_requests');
    }
}
