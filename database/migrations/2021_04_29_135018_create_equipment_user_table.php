<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_user', function (Blueprint $table) {
          $table->unsignedBigInteger('equipment_id');
          $table->unsignedBigInteger('user_id');

          $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
          $table->foreign('equipment_id')->references('id')->on('equipment')->onDelete('cascade');

          $table->primary(['equipment_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment_user');
    }
}
