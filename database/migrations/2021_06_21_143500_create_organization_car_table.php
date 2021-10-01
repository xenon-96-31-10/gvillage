<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationCarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_car', function (Blueprint $table) {
          $table->unsignedBigInteger('organization_id');
          $table->unsignedBigInteger('car_id');
          $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
          $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
          $table->primary(['organization_id','car_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organization_car');
    }
}
