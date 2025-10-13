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

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
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
