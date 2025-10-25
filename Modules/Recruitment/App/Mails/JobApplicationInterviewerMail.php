<?php
namespace Modules\Recruitment\App\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobApplicationInterviewerMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $mailData;
    public $interview;
    public $user;
    public $interviewer_name;
    public $logo_path;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData         = $mailData;
        $this->interview        = $mailData['interview'];
        $this->user             = $mailData['user'];
        $this->interviewer_name = $mailData['interviewer_name'];
        $this->logo_path        = storage_path('app/' . $mailData['logo_path']);

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
            view: 'recruitment::emails.interviewermail',
        );
    }
}
