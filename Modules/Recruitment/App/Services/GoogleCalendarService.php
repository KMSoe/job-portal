<?php

namespace Modules\Recruitment\App\Services;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Google_Service_Calendar_EventAttendee;
use Exception;

class GoogleCalendarService
{
    protected $client;
    protected $service;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(config('services.google.redirect_uri'));
        $this->client->setScopes([
            Google_Service_Calendar::CALENDAR_EVENTS,
            Google_Service_Calendar::CALENDAR
        ]);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
    }

    public function setAccessToken($token)
    {
        $this->client->setAccessToken($token);
        
        if ($this->client->isAccessTokenExpired()) {
            $refreshToken = $this->client->getRefreshToken();
            if ($refreshToken) {
                $this->client->fetchAccessTokenWithRefreshToken($refreshToken);
            }
        }
        
        $this->service = new Google_Service_Calendar($this->client);
    }

    public function createEventWithMeet($eventData)
    {
        try {
            $event = new Google_Service_Calendar_Event([
                'summary' => $eventData['summary'],
                'description' => $eventData['description'],
                'start' => [
                    'dateTime' => $eventData['start_time'],
                    'timeZone' => $eventData['timezone'] ?? config('app.timezone', 'UTC'),
                ],
                'end' => [
                    'dateTime' => $eventData['end_time'],
                    'timeZone' => $eventData['timezone'] ?? config('app.timezone', 'UTC'),
                ],
                'attendees' => $this->formatAttendees($eventData['attendees']),
                'reminders' => [
                    'useDefault' => false,
                    'overrides' => [
                        ['method' => 'email', 'minutes' => 24 * 60],
                        ['method' => 'popup', 'minutes' => 30],
                    ],
                ],
                'conferenceData' => [
                    'createRequest' => [
                        'requestId' => uniqid(),
                        'conferenceSolutionKey' => [
                            'type' => 'hangoutsMeet'
                        ],
                    ],
                ],
            ]);

            $calendarId = 'primary';
            $optParams = ['conferenceDataVersion' => 1, 'sendUpdates' => 'all'];
            
            $createdEvent = $this->service->events->insert($calendarId, $event, $optParams);

            return [
                'event_id' => $createdEvent->getId(),
                'meet_link' => $createdEvent->getHangoutLink(),
                'html_link' => $createdEvent->getHtmlLink(),
            ];
        } catch (Exception $e) {
            throw new Exception("Failed to create Google Calendar event: " . $e->getMessage());
        }
    }

    public function updateEvent($eventId, $eventData)
    {
        try {
            $calendarId = 'primary';
            $event = $this->service->events->get($calendarId, $eventId);

            if (isset($eventData['summary'])) {
                $event->setSummary($eventData['summary']);
            }
            if (isset($eventData['description'])) {
                $event->setDescription($eventData['description']);
            }
            if (isset($eventData['start_time'])) {
                $start = new Google_Service_Calendar_EventDateTime();
                $start->setDateTime($eventData['start_time']);
                $start->setTimeZone($eventData['timezone'] ?? 'UTC');
                $event->setStart($start);
            }
            if (isset($eventData['end_time'])) {
                $end = new Google_Service_Calendar_EventDateTime();
                $end->setDateTime($eventData['end_time']);
                $end->setTimeZone($eventData['timezone'] ?? 'UTC');
                $event->setEnd($end);
            }
            if (isset($eventData['attendees'])) {
                $event->setAttendees($this->formatAttendees($eventData['attendees']));
            }

            $updatedEvent = $this->service->events->update($calendarId, $eventId, $event, ['sendUpdates' => 'all']);

            return [
                'event_id' => $updatedEvent->getId(),
                'meet_link' => $updatedEvent->getHangoutLink(),
                'html_link' => $updatedEvent->getHtmlLink(),
            ];
        } catch (Exception $e) {
            throw new Exception("Failed to update Google Calendar event: " . $e->getMessage());
        }
    }

    public function deleteEvent($eventId)
    {
        try {
            $calendarId = 'primary';
            $this->service->events->delete($calendarId, $eventId, ['sendUpdates' => 'all']);
            return true;
        } catch (Exception $e) {
            throw new Exception("Failed to delete Google Calendar event: " . $e->getMessage());
        }
    }

    protected function formatAttendees($attendees)
    {
        $formattedAttendees = [];
        foreach ($attendees as $attendee) {
            $formattedAttendees[] = new Google_Service_Calendar_EventAttendee([
                'email' => $attendee['email'],
                'displayName' => $attendee['name'] ?? null,
            ]);
        }
        return $formattedAttendees;
    }

    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    public function getAuthUrlWithState(?string $state = null): string
    {
        if ($state) {
            $this->client->setState($state);
        }

        return $this->client->createAuthUrl();
    }

    public function authenticateWithCode($code)
    {
        $token = $this->client->fetchAccessTokenWithAuthCode($code);
        return $token;
    }

    public function isAccessTokenExpired()
    {
        return $this->client->isAccessTokenExpired();
    }

    public function getAccessToken()
    {
        return $this->client->getAccessToken();
    }
}