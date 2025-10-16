<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Interview Invitation - Interviewer</title>
</head>

<body
    style="
      margin: 0;
      padding: 0;
      font-family: Arial, Helvetica, sans-serif;
      background-color: #f3f4f6;
    ">
    <table width="100%" cellpadding="0" cellspacing="0" border="0"
        style="background-color: #f3f4f6; padding: 32px 16px">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" border="0"
                    style="
              max-width: 800px;
              background-color: #ffffff;
              padding: 48px;
              box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            ">
                    <!-- Header -->
                    <tr>
                        <td style="padding-bottom: 24px; border-bottom: 2px solid #000000">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td width="50%" valign="top">
                                        <!-- Logo -->
                                        <table cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td
                                                    style="
                              padding: 8px;
                              width: 80px;
                              height: 80px;
                              text-align: center;
                              vertical-align: middle;
                            ">
                                                    <img src="{{ $message->embedData($logoFile, 'logo.png') }}"
                                                        alt="{{ $interview->application->jobPosting->company->name }}"
                                                        style="width: 120px; margin-bottom: 8px;" />
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="50%" valign="top" align="right">
                                        <!-- Company Info -->
                                        <div
                                            style="
                          font-size: 11px;
                          color: #374151;
                          text-align: right;
                        ">
                                            <h1
                                                style="
                            margin: 0 0 8px 0;
                            font-weight: bold;
                            font-size: 20px;
                            color: #000000;
                            text-transform: uppercase;
                            letter-spacing: 0.05em;
                          ">
                                                {{ $interview->application->jobPosting->company->name }}
                                            </h1>
                                            <p style="margin: 4px 0">
                                                {{ $interview->application->jobPosting->company->address }}
                                            </p>
                                            <p style="margin: 4px 0">
                                                @if ($interview->application->jobPosting->company->primary_phone && $interview->application->jobPosting->company->secondary_phone)
                                                    Tel: {{ $interview->application->jobPosting->company->primary_phone }},
                                                    {{ $interview->application->jobPosting->company->secondary_phone }}
                                                @else
                                                    Tel: {{ $interview->application->jobPosting->company->primary_phone }}
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <p style="margin: 16px 0 0 0; font-size: 13px">
                                Hi {{ $interviewer_name }},
                            </p>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <p style="margin: 16px 0 0 0; font-size: 13px">
                                You have been assigned to conduct an interview for the {{ $interview->application->jobPosting->title }} position at {{ $interview->application->jobPosting->company->name }}. Please find the interview details below.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <p style="margin: 16px 0 0 0; font-size: 13px">
                                <strong>Interview Details:</strong><br>
                                <strong>Candidate:</strong> {{ $interview->application->applicant->name }}<br>
                                <strong>Position:</strong> {{ $interview->application->jobPosting->title }}<br>
                                <strong>Date:</strong> {{ $interview->scheduled_at->format('F j, Y') }}<br>
                                <strong>Time:</strong> {{ $interview->scheduled_at->format('g:i A') }}<br>
                                <strong>Location:</strong> {{ $interview->interview_type === 'offline' ? $interview->location : 'Google Meet' }}<br>
                                @if($interview->google_meet_link)
                                    <strong>Google Meet Link:</strong> <a href="{{ $interview->google_meet_link }}" style="color: #3B82F6;">{{ $interview->google_meet_link }}</a><br>
                                @endif
                                <strong>Duration:</strong> {{ $interview->duration_minutes ?? '60' }} minutes
                            </p>
                        </td>
                    </tr>

                    @if($interview->application->applicant->resume_file_path)
                    <tr>
                        <td>
                            <p style="margin: 16px 0 0 0; font-size: 13px">
                                <strong>Candidate Resume:</strong> Please review the candidate's resume before the interview.
                            </p>
                        </td>
                    </tr>
                    @endif

                    <tr>
                        <td>
                            <p style="margin: 16px 0 0 0; font-size: 13px">
                                Please confirm your availability for this interview. If you have any conflicts or need to reschedule, please let us know as soon as possible.
                            </p>
                        </td>
                    </tr>

                    @if($interview->notes)
                    <tr>
                        <td>
                            <p style="margin: 16px 0 0 0; font-size: 13px">
                                <strong>Additional Notes:</strong><br>
                                {{ $interview->notes }}
                            </p>
                        </td>
                    </tr>
                    @endif

                    <!-- Footer -->
                    <tr>
                        <td>
                            <p style="margin: 16px 0 0 0; font-size: 13px">
                                Best regards,
                            </p>
                            <div style="margin-top: 8px; font-size: 13px">
                                <p style="margin: 4px 0; font-weight: 600">{{ $user->name }}</p>
                                <p style="margin: 4px 0; font-weight: 600">{{ $user->employee?->position ?? 'HR Manager' }}</p>
                                <p style="margin: 4px 0; font-weight: 600">
                                    {{ $interview->application->jobPosting->company->name }}
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>






