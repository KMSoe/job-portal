<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_posting_id');
            $table->unsignedBigInteger('job_aplication_id');
            $table->unsignedBigInteger('candicate_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('designation_id');
            $table->unsignedBigInteger('offer_letter_template_id');
            $table->unsignedBigInteger('salary_currency_id');
            $table->double('basic_salary');
            $table->string('employment_type');
            $table->boolean('approve_required');
            $table->unsignedBigInteger('approver_id');
            $table->string('approver_signature');
            $table->date('offer_date');
            $table->date('joined_date');
            $table->string('status');
            $table->string('offer_letter_file');
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
        Schema::dropIfExists('job_offers');
    }
}
