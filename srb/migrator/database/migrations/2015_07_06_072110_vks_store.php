<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VksStore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vks_store', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->default('Не указано');
            $table->dateTime('date');
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time');
            $table->unsignedInteger('department')->nullable();
            $table->unsignedInteger('initiator')->nullable();
            //if ca is init fill this field
            $table->string('ca_code')->nullable();
            $table->string('init_customer_fio', 255)->default('Не указано');
            $table->string('init_customer_phone', 255)->default('Не указано');
            $table->string('init_customer_mail', 255)->default('Не указано');
            $table->integer('status')->default(0);
            $table->unsignedInteger('approved_by')->nullable();
            $table->string('comment_for_admin', 500)->nullable();
            $table->string('comment_for_user', 500)->nullable();
            $table->integer('presentation')->default(0);
            $table->integer('flag')->default(0);
            $table->unsignedInteger('owner_id')->default(1);
            $table->string('from_ip', 20)->nullable();
            $table->integer('is_simple')->default(0);
            $table->integer('is_private')->default(0);
            $table->integer('record_required')->default(0);
            $table->integer('in_place_participants_count')->default(0);
            $table->integer('other_tb_required')->default(0);
            $table->integer('link_ca_vks_id')->nullable();
            $table->integer('link_ca_vks_type')->nullable();
            $table->integer('vks_stack_id')->nullable();
            $table->timestamps();
            /*
            $table->foreign('department')->references('id')->on('departments')->onUpdate('no action')->onDelete('no action');
            $table->foreign('initiator')->references('id')->on('initiators')->onUpdate('no action')->onDelete('no action');
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
        Schema::drop('vks_store');
    }
}
