<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VksToCaInplaceParticipants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vks_to_ca_inplace_participants', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vks_id');
            $table->integer('participants_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vks_to_ca_inplace_participants');
    }
}
