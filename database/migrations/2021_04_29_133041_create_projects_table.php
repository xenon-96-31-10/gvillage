<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('address');
            $table->decimal('totalarea', 6, 1)->nullable();
            $table->decimal('livingarea', 6, 1)->nullable();

            $table->unsignedBigInteger('project_type_id');
            $table->foreign('project_type_id')->references('id')->on('project_types');            
            $table->unsignedBigInteger('owner_id');
            $table->foreign('owner_id')->references('id')->on('profiles')->onDelete('cascade');
            $table->unsignedBigInteger('personal_account_id')->nullable();
            $table->foreign('personal_account_id')->references('id')->on('personal_accounts');
            $table->unsignedBigInteger('organization_id');
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->unsignedBigInteger('project_status_id');
            $table->foreign('project_status_id')->references('id')->on('project_statuses');
            $table->unsignedBigInteger('pass_rate_plan_id')->nullable();
            $table->foreign('pass_rate_plan_id')->references('id')->on('pass_rate_plans');
            $table->unsignedBigInteger('project_group_id')->nullable();
            $table->foreign('project_group_id')->references('id')->on('project_groups');
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
        Schema::dropIfExists('projects');
    }
}
