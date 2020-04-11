<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');


            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects');

            $table->unsignedBigInteger('project_module_id')->nullable();
            $table->foreign('project_module_id')->references('id')->on('project_modules');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('current_project_phase_id')->nullable();
            $table->foreign('current_project_phase_id')->references('id')->on('project_phases');

            $table->string('title', 100);
            $table->text('description')->nullable();

            $table->unsignedTinyInteger('type');

            $table->dateTime('done_at')->nullable();

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
        Schema::dropIfExists('project_tasks');
    }
}
