<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicantExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_experiences', function (Blueprint $table) {
             $table->id();
            
            // Link to the Applicant
            $table->unsignedBigInteger('applicant_id');
            $table->foreign('applicant_id')->references('id')->on('applicants')->onDelete('cascade');
            
            // Job Details
            $table->string('job_title');
            $table->unsignedBigInteger('job_function_id')->nullable();
            $table->unsignedBigInteger('experience_level_id')->nullable();
            
            // Company & Industry
            $table->string('company_name');
            $table->unsignedBigInteger('industry_id')->nullable(); // Assuming an 'industries' table exists
            
            // Location
            $table->unsignedBigInteger('country_id')->nullable();
            
            // Dates & Status
            $table->date('from_date');
            $table->date('to_date')->nullable();
            $table->boolean('is_current')->default(false); // Toggle for "I currently work here"
            
            // Description
            $table->text('job_description')->nullable();
            
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
        Schema::dropIfExists('applicant_experiences');
    }
}
