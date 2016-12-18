<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoleIDToLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('StageLogs', function ($table) {
            $table->integer('roleID')->nullable();
        });

        Schema::table('Negotiations', function ($table) {
            $table->string('lanID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('StageLogs', function ($table) {
            $table->dropColumn('roleID');
        });

        Schema::table('Negotiations', function ($table) {
           $table->dropColumn('lanID');
        });
    }
}
