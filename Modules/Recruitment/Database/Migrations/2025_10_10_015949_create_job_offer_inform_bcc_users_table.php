<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobOfferInformBccUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_offer_inform_bcc_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_offer_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('designation_id')->default(0);
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
        Schema::dropIfExists('job_offer_inform_bcc_users');
    }
}
