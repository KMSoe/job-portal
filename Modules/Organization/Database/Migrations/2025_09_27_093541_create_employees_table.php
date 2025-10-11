<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Organization\App\Enums\EmploymentTypes;
use Modules\Organization\App\Enums\GenderTypes;
use Modules\Organization\App\Enums\MaritalStatuses;

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
            $table->string('name');
            $table->string('preferred_name')->nullable();
            $table->string('email')->unique();
            $table->string('work_mail')->nullable();
            $table->unsignedBigInteger('company_id')->default(0);
            $table->unsignedBigInteger('department_id')->default(0);
            $table->unsignedBigInteger('designation_id')->default(0);
            $table->enum('employment_type', array_column(EmploymentTypes::cases(), 'value'))->default(EmploymentTypes::PERMANENT->value);
            $table->enum('gender', array_column(GenderTypes::cases(), 'value'))->default(GenderTypes::MALE->value);
            $table->enum('marital_status', array_column(MaritalStatuses::cases(), 'value'))->default(MaritalStatuses::SINGLE->value);
            $table->string('nationality')->nullable();
            $table->string('race')->nullable();
            $table->string('religion')->nullable();
            $table->string('primary_phone_dial_code')->nullable();
            $table->string('primary_phone_no')->nullable();
            $table->string('secondary_phone_dial_code')->nullable();
            $table->string('secondary_phone_no')->nullable();
            $table->string('id_nrc')->nullable();
            $table->string('passport')->nullable();
            $table->text('address')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->unsignedBigInteger('salary_currency_id')->nullable();
            $table->string('basic_salary')->nullable();
            $table->string('employee_code')->unique();
            $table->date('offered_date')->nullable();
            $table->date('joined_date')->nullable();
            $table->date('last_date')->nullable();
            $table->unsignedBigInteger('onboarding_checklist_template_id')->default(0);
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
