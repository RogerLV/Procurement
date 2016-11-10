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
            $table->string('givenID')->nullable();
            $table->string('lanID', 20);
            $table->string('dept', 10);
            $table->string('scope', 20);
            $table->string('name', env('FIELD_MAX_LENGTH'));
            $table->integer('stage');
            $table->string('background', env('FIELD_MAX_LENGTH'));
            $table->string('budget');
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
            $table->string('documentable_type');
            $table->integer('documentable_id');
            $table->integer('type');
            $table->string('originalName', env('FIELD_MAX_LENGTH'));
            $table->string('subAddress', env('FIELD_MAX_LENGTH'));
            $table->string('tempName');
            $table->string('ext');
            $table->string('mimeType');
            $table->string('lanID');

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

        Schema::create('ProjectStageLogs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('logable_type');
            $table->integer('logable_id');
            $table->integer('fromStage');
            $table->integer('toStage');
            $table->string('dept')->nullable();
            $table->string('lanID', 20);
            $table->string('comment', env('FIELD_MAX_LENGTH'))->nullable();
            $table->dateTime('timeAt');
        });

        Schema::create('Conversation', function (Blueprint $table) {
            $table->increments('id');
            $table->string('conversable_type');
            $table->integer('conversable_id');
            $table->string('lanID', 20);
            $table->string('content', env('FIELD_MAX_LENGTH'));

            $table->timestamps();
            $table->softDeletes();
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
        Schema::drop('ProjectStageLogs');
        Schema::drop('Conversation');
    }
}
