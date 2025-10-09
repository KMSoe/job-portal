<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->unsignedBigInteger('salary_currency_id')->nullable();
            $table->double('basic_salary')->nullable();
            $table->string('employment_type')->nullable();
            $table->boolean('approve_required')->default(true);
            $table->unsignedBigInteger('approver_id')->nullable();
            $table->unsignedBigInteger('approver_position_id')->nullable();
            $table->string('approver_signature')->nullable();
            $table->date('offer_date');
            $table->date('joined_date')->nullable();
            $table->string('status');
            $table->unsignedBigInteger('created_by')->default(0);
            $table->unsignedBigInteger('updated_by')->default(0);
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
