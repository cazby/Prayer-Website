<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupInvitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_invites', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned();
            $table->integer('sender_id')->unsigned();
            $table->integer('receiver_id')->unsigned()->nullable();
            $table->string('email');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();

            $table
                ->foreign('group_id')
                ->references('id')
                ->on('groups')
                ->onDelete('cascade');

            $table
                ->foreign('sender_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table
                ->foreign('receiver_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('group_invites');
    }
}
