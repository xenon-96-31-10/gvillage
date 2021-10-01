<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePassRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pass_rates', function (Blueprint $table) {
            $table->id();
            $table->string('pass');
            $table->string('visitor');
            $table->string('visitor_type');
            $table->string('role');
            $table->string('time');
            $table->decimal('price', 6, 2);
            $table->text('description');
            $table->unsignedBigInteger('pass_rate_plan_id');
            $table->foreign('pass_rate_plan_id')->references('id')->on('pass_rate_plans')->onDelete('cascade');
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
        Schema::dropIfExists('pass_rates');
    }
}
