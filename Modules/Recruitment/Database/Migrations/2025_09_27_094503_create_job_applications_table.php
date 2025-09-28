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
// --- Foreign Keys (The Core Relationship) ---
            // Links to the candidate's profile
            $table->foreignId('applicant_id')->constrained('applicants')->onDelete('cascade');

            // Links to the specific job post
            $table->foreignId('job_posting_id')->constrained('job_postings')->onDelete('cascade');

            // Ensures a candidate can only apply to the same job once
            $table->unique(['applicant_id', 'job_posting_id']);

            // --- Application Details ---
            $table->enum('status', [
                'received',
                'under_review',
                'interview',
                'technical_test',
                'offer_extended',
                'hired',
                'rejected',
                'withdrawn',
            ])->default('received');

            $table->timestamp('applied_at')->useCurrent();
            $table->string('source')->nullable()->comment('e.g., LinkedIn, Referal, Company Portal');
            $table->decimal('expected_salary', 10, 2)->nullable();
            $table->text('cover_letter_content')->nullable();

            // --- Attachments (Store JSON array of file paths) ---
            $table->json('attachments')->nullable()->comment('JSON array of file paths for CV, portfolio, etc.');

            // --- Feedback/Historical Data ---
            $table->text('internal_notes')->nullable()->comment('Notes by HR/Recruiter.');
            $table->text('rejection_reason')->nullable()->comment('Reason for rejection, if applicable.');

            // --- Auditing ---
            $table->foreignId('reviewed_by_user_id')->nullable()->constrained('users')->onDelete('set null')->comment('HR user who last reviewed the application.');

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
