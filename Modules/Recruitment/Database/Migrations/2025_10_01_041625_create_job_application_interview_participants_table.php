<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobApplicationInterviewParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_application_interview_interviewers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('interview_id');
            $table->unsignedBigInteger('user_id');
            $table->string('attendance_status', 20)->default('confirmed');
            $table->decimal('score')->nullable();
            $table->text('feedbak')->nullable();
            $table->timestamp('commented_at')->nullable();
            $table->string('comment_status')->nullable(); // pending, draft, done
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
        Schema::dropIfExists('job_application_interview_interviewers');
    }
}
