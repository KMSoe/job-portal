<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Employee\App\Enums\EmploymentTypes;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('company_id')->default(0);
            $table->string('employee_code')->unique();
            $table->string('name');
            $table->string('phone_dial_code');
            $table->string('phone_no');
            $table->date('offered_date')->nullable();
            $table->date('joined_date')->nullable();
            $table->date('last_date')->nullable();
            $table->unsignedBigInteger('department_id')->default(0);
            $table->unsignedBigInteger('designation_id')->default(0);
            $table->enum('employment_type', array_column(EmploymentTypes::cases(), 'value'))->default(EmploymentTypes::PERMANENT->value);
            $table->unsignedBigInteger('salary_currency_id')->nullable();
            $table->string('basic_salary')->nullable();
            // columns
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
        Schema::dropIfExists('employees');
    }
}
