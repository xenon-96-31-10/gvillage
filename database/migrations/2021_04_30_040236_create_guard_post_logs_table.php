<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuardPostLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guard_post_logs', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->unsignedBigInteger('guard_post_id');
            $table->foreign('guard_post_id')->references('id')->on('guard_posts')->onDelete('cascade');
            $table->unsignedBigInteger('guard_post_enter_id');
            $table->foreign('guard_post_enter_id')->references('id')->on('guard_post_enters')->onDelete('cascade');
            $table->unsignedBigInteger('guard_id');
            $table->foreign('guard_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('guard_post_logs');
    }
}
