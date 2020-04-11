<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTaskPhasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_task_phases', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('project_task_id');
            $table->foreign('project_task_id')->references('id')->on('project_tasks');

            $table->unsignedBigInteger('project_phase_id');
            $table->foreign('project_phase_id')->references('id')->on('project_phases');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('remarks')->nullable();

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
        Schema::dropIfExists('project_task_phases');
    }
}
