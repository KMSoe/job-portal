<?php
namespace Modules\Recruitment\App\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobOfferInformToDepartmentsMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $mailData;
    public $job_offer;
    public $logo_path;
    public $candicate_name;
    public $candicate_position;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData           = $mailData;
        $this->job_offer          = $mailData['job_offer'];
        $this->logo_path          = storage_path('app/' . $mailData['logo_path']);
        $this->candicate_name     = $mailData['candicate_name'];
        $this->candicate_position = $mailData['candicate_position'];
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: "New Job Offer Made",
        );
    }

    public function content()
    {
        return new Content(
            view: 'recruitment::emails.jobofferinformmail',
        );
    }
}
