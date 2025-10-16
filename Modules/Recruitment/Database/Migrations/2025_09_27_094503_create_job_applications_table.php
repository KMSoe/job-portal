<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('applicant_id');
            $table->unsignedBigInteger('job_posting_id');
            $table->unique(['applicant_id', 'job_posting_id']);

            $table->string('status');

            $table->timestamp('applied_at')->useCurrent();
            $table->decimal('expected_salary', 10, 2)->nullable();

            $table->unsignedBigInteger('resume_id');
            $table->longText('recruiter_comment')->nullable();
            $table->unsignedBigInteger('last_updated_by')->default(0);
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
        Schema::dropIfExists('job_applications');
    }
}
