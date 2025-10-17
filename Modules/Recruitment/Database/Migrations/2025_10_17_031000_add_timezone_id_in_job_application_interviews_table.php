<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimezoneIdInJobApplicationInterviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_application_interviews', function (Blueprint $table) {
            $table->unsignedBigInteger('timezone_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_application_interviews', function (Blueprint $table) {
            $table->dropColumn('timezone_id');
        });
    }
}
