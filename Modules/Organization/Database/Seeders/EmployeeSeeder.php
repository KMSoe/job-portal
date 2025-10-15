<?php
namespace Modules\Organization\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Organization\App\Enums\EmploymentTypes;
use Modules\Organization\Entities\Employee;
use Modules\Storage\App\Classes\LocalStorage;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $employeesData = [
            [
                'name'               => 'Kaung Myat Soe',
                'preferred_name'     => 'Kaung',
                'email'              => 'kaungmyatsoe.m192@gmail.com',
                'employee_code'      => 'EMP-0001',
                'basic_salary'       => 6500,
                'gender'             => 'male',
                'employment_type'    => EmploymentTypes::PERMANENT->value,
                'department_id'      => 1,
                'designation_id'     => 1,
                'marital_status'     => 'single',
                'nationality'        => 'Singaporean',
                'id_nrc'             => 'S8123456A',
                'bank_name'          => 'DBS',
                'bank_account_no'    => '1012345678',
                'joined_date'        => '2024-03-01',
                'primary_phone_no'   => '81234560',
                'secondary_phone_no' => '91234560',
                'passport_number'    => 'E1234567',
                'address'            => '123 Example St, Singapore',
            ],
            [
                'name'               => 'Ye Myat Sandi Oo',
                'preferred_name'     => 'Sandi',
                'email'              => 'yemyatsandi@gmail.com',
                'employee_code'      => 'EMP-0002',
                'basic_salary'       => 5000,
                'gender'             => 'female',
                'employment_type'    => EmploymentTypes::PERMANENT->value,
                'department_id'      => 2,
                'designation_id'     => 3,
                'marital_status'     => 'married',
                'nationality'        => 'Malaysian',
                'id_nrc'             => 'M9876543B',
                'bank_name'          => 'OCBC',
                'bank_account_no'    => '7076543210',
                'joined_date'        => '2025-01-15',
                'primary_phone_no'   => '81234561',
                'secondary_phone_no' => '91234561',
                'passport_number'    => 'E1234568',
                'address'            => '456 Tech Ave, Kuala Lumpur',
            ],
            [
                'name'               => 'Thuyain Soe',
                'preferred_name'     => 'Thuyain',
                'email'              => 'thuyainsoe163361@gmail.com',
                'employee_code'      => 'EMP-0003',
                'basic_salary'       => 3200,
                'gender'             => 'male',
                'employment_type'    => EmploymentTypes::PERMANENT->value,
                'department_id'      => 3,
                'designation_id'     => 5,
                'marital_status'     => 'single',
                'nationality'        => 'Indonesian',
                'id_nrc'             => 'A1122334C',
                'bank_name'          => 'UOB',
                'bank_account_no'    => '4045566778',
                'joined_date'        => '2025-06-20',
                'primary_phone_no'   => '81234562',
                'secondary_phone_no' => '91234562',
                'passport_number'    => 'E1234569',
                'address'            => '789 Financial Road, Jakarta',
            ],
            [
                'name'               => 'Naing Aung Zaw',
                'preferred_name'     => 'Naing',
                'email'              => 'naingaung9863@gmail.com',
                'employee_code'      => 'EMP-0004',
                'basic_salary'       => 7800,
                'gender'             => 'male',
                'employment_type'    => EmploymentTypes::PERMANENT->value,
                'department_id'      => 4,
                'designation_id'     => 7,
                'marital_status'     => 'single',
                'nationality'        => 'Thai',
                'id_nrc'             => 'T9988776D',
                'bank_name'          => 'Standard Chartered',
                'bank_account_no'    => '2029988776',
                'joined_date'        => '2023-10-10',
                'primary_phone_no'   => '81234563',
                'secondary_phone_no' => '91234563',
                'passport_number'    => 'E1234570',
                'address'            => '101 Trade Center, Bangkok',
            ],
        ];

        $baseAttributes = [
            'company_id'                => 1,
            'race'                      => 'Asian',
            'religion'                  => 'None',
            'primary_phone_dial_code'   => '+65',
            'secondary_phone_dial_code' => '+65',
            'salary_currency_id'        => 1,
            'last_date'                 => null,
        ];

        $localFilePath = public_path('sample_files/avator.png');
        $localFile     = new File($localFilePath);

        $storage  = new LocalStorage();
        $filePath = $storage->store('user_profiles', $localFile);
        DB::beginTransaction();
        foreach ($employeesData as $index => $data) {
            $employeeAttributes = array_merge($baseAttributes, $data);

            $user = User::create([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'password'          => Hash::make('password@123'),
                'photo'             => $filePath,
                'email_verified_at' => now(),
            ]);

            $employeeDataForModel            = $employeeAttributes;
            $employeeDataForModel['user_id'] = $user->id;

            $employee = Employee::create($employeeDataForModel);
            $user->update(['employee_id' => $employee->id]);
        }

        DB::commit();
    }
}
