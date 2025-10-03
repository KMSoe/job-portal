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

            // --- Application Details ---
            $table->string('status');

            $table->timestamp('applied_at')->useCurrent();
            $table->decimal('expected_salary', 10, 2)->nullable();

            // --- Attachments (Store JSON array of file paths) ---
            $table->unsignedBigInteger('resume_id')->nullable();

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
