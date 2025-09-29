<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicantEducationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_education', function (Blueprint $table) {
            $table->id();
            
            // Link to the Applicant
            $table->unsignedBigInteger('applicant_id');
            
            // Institution & Degree Details
            $table->string('location')->nullable();
            $table->string('school');
            $table->string('degree_level');
            $table->string('area_of_study');
            
            // Location
            $table->unsignedBigInteger('country_id')->nullable();
            
            // Dates
            $table->date('from_date');
            $table->date('to_date')->nullable();            
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
        Schema::dropIfExists('applicant_education');
    }
}
