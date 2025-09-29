<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobPostingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            // --- Organizational Foreign Keys ---
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('designation_id');

            $table->unsignedBigInteger('template_id');

            // --- Job Details ---
            $table->string('title');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('experience_level_id');
            $table->unsignedBigInteger('job_function_id');
            $table->unsignedBigInteger('min_eduction_level_id');

            $table->longText('summary');
            $table->string('open_to')->nullable();
            $table->longText('roles_and_responsibilities')->nullable();
            $table->longText('requirements')->nullable();
            $table->boolean('what_we_can_offer_include')->default(false);
            // if what_we_can_offer_include = true
            $table->text('what_we_can_offer_benefits')->nullable();
            $table->text('what_we_can_offer_highlights')->nullable();
            $table->text('what_we_can_offer_career_opportunities')->nullable();

            // --- Type and Location ---
            $table->enum('job_type', ['full-time', 'part-time', 'contract', 'internship'])->default('full-time');
            $table->enum('work_arrangement', ['remote', 'hybrid', 'on-site'])->default('on-site');
            // if work_arrangement = 'hybrid', 'on-site'
            $table->string('location')->nullable();

            // --- Compensation ---
            $table->enum('salary_type', ['range', 'up_to', 'around', 'fixed', 'negotiable'])->default('range');

            // currency
            $table->unsignedBigInteger('salary_currency_id')->nullable();

            // Maximum value (used ONLY for 'up_to, around, fixed')
            $table->decimal('salary_amount', 12, 2)->nullable();
            // Maximum value (used ONLY for 'range')
            $table->decimal('min_salary', 12, 2)->nullable();

            // Maximum value (used ONLY for 'range')
            $table->decimal('max_salary', 12, 2)->nullable();

            // Optional field for additional notes (e.g., "plus bonus")
            $table->string('salary_notes')->nullable();

            // --- Status and Dates ---
            $table->integer('vacancies')->default(1);
            $table->enum('status', ['draft', 'pending_approval', 'published', 'archived', 'closed'])->default('draft');
            $table->timestamp('published_at')->nullable()->index(); // Index for quick lookup of live jobs
            $table->timestamp('deadline_date')->nullable();

            // --- Auditing ---
            $table->unsignedBigInteger('created_by')->default(0);
            $table->unsignedBigInteger('updated_by')->default(0);

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
        Schema::dropIfExists('job_postings');
    }
}
