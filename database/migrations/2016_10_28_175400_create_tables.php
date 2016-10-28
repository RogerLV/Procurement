<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Projects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('year');
            $table->string('lanID', 20);
            $table->string('dept', 10);
            $table->string('scope', 20);
            $table->string('name', env('FIELD_MAX_LENGTH'));
            $table->string('background', env('FIELD_MAX_LENGTH'));
            $table->float('amount');
            $table->boolean('involveReview');
            $table->integer('memberAmount');
            $table->string('approach', 50);
            $table->boolean('selectVendors');
            $table->string('summary', env('FIELD_MAX_LENGTH'));

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ProjectRoleDepartments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('projectID');
            $table->string('dept', 10);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ProjectRoles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('roleDeptID');
            $table->string('lanID', 20);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('Documents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('referenceTable');
            $table->integer('referenceID');
            $table->string('type');
            $table->string('name', env('FIELD_MAX_LENGTH'));
            $table->string('address', env('FIELD_MAX_LENGTH'));

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('DueDiligence', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('projectID');
            $table->string('type', 1); // Q or A
            $table->integer('QID');
            $table->string('content', env('FIELD_MAX_LENGTH'));

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ScoreItems', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('projectID');
            $table->string('content', env('FIELD_MAX_LENGTH'));
            $table->integer('weight');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('Scores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('itemID');
            $table->integer('score');

            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('SystemRoles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('roleID');
            $table->string('lanID', 20);
            $table->string('dept', 10);
            $table->boolean('active');

            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('ApproveLogs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('projectID');
            $table->integer('fromPhase');
            $table->integer('toPhase');
            $table->string('approveBy', 20);
            $table->dateTime('timeAt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Projects');
        Schema::drop('ProjectRoleDepartments');
        Schema::drop('ProjectRoles');
        Schema::drop('Documents');
        Schema::drop('DueDiligence');
        Schema::drop('ScoreItems');
        Schema::drop('Scores');
        Schema::drop('SystemRoles');
        Schema::drop('ApproveLogs');
    }
}
