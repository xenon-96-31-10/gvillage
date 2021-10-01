<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermanentPassDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permanent_pass_dates', function (Blueprint $table) {
            $table->id();
            $table->timestamp('entry');
            $table->timestamp('exit')->nullable();
            $table->unsignedBigInteger('pass_id');
            $table->foreign('pass_id')->references('id')->on('permanent_passes')->onDelete('cascade');
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
        Schema::dropIfExists('permanent_pass_dates');
    }
}
