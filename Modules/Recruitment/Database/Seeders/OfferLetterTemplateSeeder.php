<?php
namespace Modules\Recruitment\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfferLetterTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $content = "
We are pleased to inform you that you are appointed as an {{job_title}} at {{company_name}}
Department with effect from {{offer_date}}.

Your salary will be {{basic_salary}} per month, your hours of work will be Monday to Saturday,
9:00 AM to 5:00 PM and as a condition of employment, you will be required to serve a 3 months
probationary period. If further evaluation of performance is required, the probationary period
will be extended from 3 to 6 months. It is our hope that your career with Royal Motor Co., Ltd.
will be rewarding and challenging. Please confirm your acceptance of this offer by signing the
enclosed copy and returning it to Human Resources Department. We look forward to your
continual support and wish you every success in your employment with Royal Motor Co., Ltd.
Thank you for your prompt attention to this request. If you have any questions or concerns,
please feel free to contact our office at 09-798236943.
        ";

        // Template data based on the provided JSON structure
        $templateData = [
            'is_showed_offer_date' => true,
            'is_showed_ref'        => true,
            'is_showed_subject'    => true,
            // Note: I'm replacing the simple "{{applicant_name}}" content placeholder
            // with the actual offer letter text.
            'content'              => trim($content),
        ];

        DB::table('offer_letter_templates')->insert([
            'name'              => 'Offer Letter Template',
            'description'       => 'Offer Letter.',
            'company_id'        => 1,
            'is_salary_visible' => true,
            'is_active'         => true,
            // JSON-encode the template_data array
            'template_data'     => json_encode($templateData),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
    }
}
