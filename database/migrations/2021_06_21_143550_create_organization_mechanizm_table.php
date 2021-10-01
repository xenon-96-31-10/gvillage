<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationMechanizmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_mechanizm', function (Blueprint $table) {
          $table->unsignedBigInteger('organization_id');
          $table->unsignedBigInteger('mechanizm_id');
          $table->foreign('mechanizm_id')->references('id')->on('mechanizms')->onDelete('cascade');
          $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
          $table->primary(['organization_id','mechanizm_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organization_mechanizm');
    }
}
