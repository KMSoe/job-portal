<?php
namespace Modules\Recruitment\App\Services;

use Carbon\Carbon;
use Spatie\GoogleCalendar\Event;

class JobApplicationInterviewService
{

    public function createInterview()
    {
        $event = new Event();

        $event->name          = 'A new event';
        $event->description   = 'Event description';
        $event->startDateTime = Carbon::now()->addHour();
        $event->endDateTime   = Carbon::now()->addHours(2);
        $event->addAttendee([
            'email'          => 'john@example.com',
            'name'           => 'John Doe',
            'comment'        => 'Lorum ipsum',
            'responseStatus' => 'needsAction',
        ]);
        $event->addAttendee(['email' => 'visibleone.stone@gmail.com']);
        $event->addMeetLink(); // optionally add a google meet link to the event

        $event->save();

        dd($event);
    }

}
