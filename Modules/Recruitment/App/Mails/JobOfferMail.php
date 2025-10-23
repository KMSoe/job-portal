<?php
namespace Modules\Recruitment\App\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class JobOfferMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;
    public $logo;
    public $job_offer;
    public $candicate_name;
    public $candicate_position;
    public $accept_url;
    public $decline_url;

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
        $this->accept_url         = URL::signedRoute('applicant.job-offer.accept-action', [
            'id'   => $this->job_offer->id,
            'hash' => sha1($this->job_offer->id), // Add extra security/token if needed
        ]);

        // Generate a secure, temporary, signed URL for Decline
        $this->decline_url = URL::signedRoute('applicant.job-offer.decline-action', [
            'id'   => $this->job_offer->id,
            'hash' => sha1($this->job_offer->id), // Add extra security/token if needed
        ]);
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
            markdown: 'recruitment::emails.joboffermail',
            with: [
                'accept_url'  => $this->accept_url,
                'decline_url' => $this->decline_url,
            ],
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
