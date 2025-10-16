<?php

namespace Modules\Recruitment\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Modules\Calendar\App\Models\ExternalCalendar;
use Modules\Calendar\App\Services\IcsImportService;
use Modules\Recruitment\Entities\JobApplicationInterview;

class InterviewRemainder extends Command
{
    protected $signature = 'interview:remainder';
    protected $description = 'Send reminders for upcoming interviews';

    public function handle()
    {
        $today = now()->toDateString();
        $interviews = JobApplicationInterview::whereDate('scheduled_at', $today)
                        ->where('scheduled_at', '<=', now()->addMinutes(60))
                        ->where('reminder_sent', false)
                        ->with(['application.applicant', 'interviewers.user'])
                        ->get();

        foreach ($interviews as $interview) {
            $interviewer_mails = $interview->interviewers->pluck('user.email')->unique()->toArray();

            Mail::send('recruitment::emails.interview_reminder', ['interview' => $interview], function($message) use ($interview, $interviewer_mails) {
                $message->to($interviewer_mails);
                $message->subject('Interview Reminder For ' . $interview->title);
            });

            Mail::send('recruitment::emails.interview_reminder', ['interview' => $interview], function($message) use ($interview) {
                $message->to($interview->application->applicant->email);
                $message->subject('Interview Reminder For ' . $interview->title);
            });

            $interview->update(['reminder_sent' => true]);
        }

        $this->info('Interview reminders sent successfully.');
    }
}
