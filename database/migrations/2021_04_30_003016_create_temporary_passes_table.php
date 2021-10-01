<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemporaryPassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temporary_passes', function (Blueprint $table) {
            $table->id();
            $table->timestamp('entry');
            $table->timestamp('exit')->nullable();
            $table->string('type');
            $table->integer('visitor_id')->onDelete('cascade');
            $table->string('visitor_type');
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
        Schema::dropIfExists('temporary_passes');
    }
}
