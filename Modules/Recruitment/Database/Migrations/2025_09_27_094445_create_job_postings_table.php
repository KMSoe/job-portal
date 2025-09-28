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
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade')->comment('The company posting the job.');
            $table->foreignId('designation_id')->constrained('designations')->onDelete('restrict')->comment('The formal designation for the role.');
            $table->foreignId('department_id')->constrained('departments')->onDelete('restrict')->comment('The department where the job resides.');

            // --- Job Details ---
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->longText('requirements')->nullable();

            // --- Type and Location ---
            $table->enum('employment_type', ['full-time', 'part-time', 'contract', 'internship'])->default('full-time');
            $table->enum('location_type', ['remote', 'hybrid', 'on-site'])->default('on-site');
            $table->string('location')->nullable()->comment('Specific city, region, or address if not fully remote.');

            // --- Compensation ---
            $table->decimal('min_salary', 10, 2)->nullable();
            $table->decimal('max_salary', 10, 2)->nullable();

            // --- Status and Dates ---
            $table->unsignedInteger('vacancies')->default(1);
            $table->enum('status', ['draft', 'published', 'archived', 'closed'])->default('draft');
            $table->timestamp('published_at')->nullable()->index(); // Index for quick lookup of live jobs
            $table->timestamp('expires_at')->nullable();

            // --- Auditing ---
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->comment('The user who created the posting.');

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
