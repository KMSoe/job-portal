<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>New Employee Onboarded</title>
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
                                                        alt="{{ $employee->company->name }}"
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
                                                {{ $employee->company->name }}
                                            </h1>
                                            <p style="margin: 4px 0">
                                                {{ $employee->company->address }}
                                            </p>
                                            <p style="margin: 4px 0">
                                                @if ($employee->company->primary_phone && $employee->company->secondary_phone)
                                                    Tel: {{ $employee->company->primary_phone }},
                                                    {{ $employee->company->secondary_phone }}
                                                @else
                                                    Tel: {{ $employee->company->primary_phone }}
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
                                Dear Team,
                            </p>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <p style="margin: 16px 0 0 0; font-size: 13px">
                                We are pleased to announce that <strong>{{ $employee->name }}</strong> has been successfully onboarded to our organization and will be joining us as {{ $employee->position ?? 'a new team member' }}.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <p style="margin: 16px 0 0 0; font-size: 13px">
                                <strong>Employee Details:</strong><br>
                                <strong>Name:</strong> {{ $employee->name }}<br>
                                <strong>Position:</strong> {{ $employee->designation?->name ?? 'To be assigned' }}<br>
                                @if($employee->department)
                                    <strong>Department:</strong> {{ $employee->department?->name }}<br>
                                @endif
                                @if($employee->start_date)
                                    <strong>Start Date:</strong> {{ $employee->start_date->format('F j, Y') }}<br>
                                @endif
                                @if($employee->email)
                                    <strong>Email:</strong> {{ $employee->email }}
                                @endif
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <p style="margin: 16px 0 0 0; font-size: 13px">
                                Please join us in welcoming {{ $employee->name }} to the team. We look forward to their contributions and encourage everyone to help them settle into their new role.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td>
                            <p style="margin: 16px 0 0 0; font-size: 13px">
                                Best regards,
                            </p>
                            <div style="margin-top: 8px; font-size: 13px">
                                <p style="margin: 4px 0; font-weight: 600">HR Team</p>
                                <p style="margin: 4px 0; font-weight: 600">
                                    {{ $employee->company->name }}
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
