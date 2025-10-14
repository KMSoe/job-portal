<?php
namespace Modules\Recruitment\App\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobOfferMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;
    public $logo;
    public $job_offer;
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
        $this->logo               = $mailData['logo'];
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
            subject: $this->mailData['subject'],
        );
    }

    public function content()
    {
        return new Content(
            view: 'recruitment::emails.joboffermail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        $offer_attachments = $this->mailData['attachments'];

        $attachments = [];

        if ($this->mailData['offer_letter_file_path']) {
            $attachments[] = Attachment::fromPath(storage_path('app/' . $this->mailData['offer_letter_file_path']));
        }

        foreach ($offer_attachments as $key => $attachment) {
            $attachments[] = Attachment::fromPath(storage_path('app/' . $attachment->file_path));
        }

        return $attachments;
    }
}
