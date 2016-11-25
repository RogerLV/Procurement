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
            $table->integer('memberAmount')->nullable();
            $table->string('approach', 50)->nullable();
            $table->boolean('selectVendors')->nullable();
            $table->string('summary', env('FIELD_MAX_LENGTH'))->nullable();

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
            $table->string('request', env('FIELD_MAX_LENGTH'));
            $table->string('answer', env('FIELD_MAX_LENGTH'))->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ScoreTemplate', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('name');
            $table->string('nameID');
            $table->string('item');
            $table->string('content', env('FIELD_MAX_LENGTH'));
            $table->integer('bottom');
            $table->integer('top');
            $table->string('comment', env('FIELD_MAX_LENGTH'))->nullable();

            $table->timestamps();
        });

        Schema::create('ScoreItems', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('projectID');
            $table->string('item');
            $table->string('content', env('FIELD_MAX_LENGTH'));
            $table->string('comment', env('FIELD_MAX_LENGTH'))->nullable();
            $table->integer('weight');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('Scores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vendorID');
            $table->string('lanID');
            $table->integer('itemID');
            $table->integer('score');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('Vendors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('name', 500);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ProjectVendorMapping', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('projectID');
            $table->integer('vendorID');
        });

        Schema::create('Negotiations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('projectID');
            $table->integer('roundNo');
            $table->string('time');
            $table->string('venue');
            $table->string('participants', 500);
            $table->string('content', env('FIELD_MAX_LENGTH'));

            $table->timestamps();
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
            $table->string('data1')->nullable();
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

        Schema::create('ReviewMeetings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('year');
            $table->string('givenID')->nullable();
            $table->integer('stage');
            $table->string('lanID');
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->string('venue')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ReviewTopics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reviewMeetingID');
            $table->string('topicable_type');
            $table->integer('topicable_id');
            $table->string('type');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('PutRecords', function (Blueprint $table) {
            $table->increments('id');
            $table->string('content', 500);

            $table->timestamps();
        });

        Schema::create('ReviewParticipants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reviewMeetingID');
            $table->string('lanID');
            $table->integer('roleID');
            $table->boolean('willAttend')->nullable();

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
        Schema::drop('ScoreTemplate');
        Schema::drop('ScoreItems');
        Schema::drop('Scores');
        Schema::drop('Vendors');
        Schema::drop('ProjectVendorMapping');
        Schema::drop('Negotiations');
        Schema::drop('SystemRoles');
        Schema::drop('ProjectStageLogs');
        Schema::drop('Conversation');
        Schema::drop('ReviewMeetings');
        Schema::drop('ReviewTopics');
        Schema::drop('PutRecords');
        Schema::drop('ReviewParticipants');
    }
}
