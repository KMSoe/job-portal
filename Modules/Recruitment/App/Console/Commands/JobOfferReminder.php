<?php
namespace Modules\Recruitment\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Modules\Recruitment\App\Enums\JobOfferStatusTypes;
use Modules\Recruitment\App\Mails\JobOfferMail;
use Modules\Recruitment\Entities\JobOffer;
use Modules\Storage\App\Classes\LocalStorage;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class JobOfferReminder extends Command
{
    protected $signature   = 'job_offer:remainder';
    protected $description = 'Send reminders for Pending Job Offers';

    private $storage;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(LocalStorage $storage)
    {
        parent::__construct();
        $this->storage = $storage;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $job_offers = JobOffer::with([
            // Core Belongs To Relationships
            'jobPosting',
            'application',
            'candidate',
            'company',
            'department',
            'designation',
            'template',
            'salaryCurrency',
            'approver',
            'approverPosition',

            // Has Many / Belongs To Many Relationships
            'attachments',
            'informedDepartments',
            'ccUsers',
            'bccUsers',

            // Note: If 'ccUsers' and 'bccUsers' pivot data is needed,
            // you can access it via $jobOffer->ccUsers[0]->pivot->designation_id
        ])
            ->where('status', JobOfferStatusTypes::SENT->value)
            ->get();

        foreach ($job_offers as $key => $job_offer) {
            $logoFile   = $this->storage->getFile($job_offer->company?->logo);
            $mimeType   = $this->storage->getMimeType($job_offer->company?->logo);
            $logoBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($logoFile);

            $mailData = [
                'subject'                => "$job_offer->offer_letter_subject - Reminder",
                'offer_letter_file_path' => $job_offer->offer_letter_file_path,
                'attachments'            => $job_offer->attachments,
                'logo'                   => $logoBase64,
                'logoFile'               => $logoFile, // Pass the raw contents
                'logoMime'               => $mimeType,
                'job_offer'              => $job_offer,
                'candicate_name'         => $job_offer->candidate?->name,
                'candicate_position'     => $job_offer->jobPosting?->title,
            ];

            try {
                Mail::to([$job_offer->candidate?->email])
                    ->cc($job_offer->ccUsers->pluck('email')->toArray())
                    ->bcc($job_offer->bccUsers->pluck('email')->toArray())
                    ->send(new JobOfferMail($mailData));
            } catch (\Throwable $th) {
                \Illuminate\Support\Facades\Log::error($th->getMessage());
            }
        }

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
