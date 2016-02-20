<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateVksToCaIdParticipants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vks_to_ca_id_participants', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vks_id');
            $table->unsignedInteger('ca_att_id');
            $table->string('user_message', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vks_to_ca_id_participants');
    }
}
