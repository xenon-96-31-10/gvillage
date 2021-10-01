<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalAccountLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_account_logs', function (Blueprint $table) {
            $table->id();
            $table->boolean('status');
            $table->decimal('total', 10, 2);
            $table->unsignedBigInteger('account_id');
            $table->foreign('account_id')->references('id')->on('personal_accounts')->onDelete('cascade');
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
        Schema::dropIfExists('personal_account_logs');
    }
}
