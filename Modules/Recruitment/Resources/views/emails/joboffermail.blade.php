<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $job_offer->offer_letter_subject }}</title>
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
                                                    <img src="{{ $message->embedData($mailData['logoFile'], 'logo.png') }}"
                                                        alt="{{ $job_offer->company->name }}"
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
                                                {{ $job_offer->company->name }}
                                            </h1>
                                            <p style="margin: 4px 0">
                                                {{ $job_offer->company->address }}
                                            </p>
                                            <p style="margin: 4px 0">
                                                @if ($job_offer->company->primary_phone && $job_offer->company->secondary_phone)
                                                    Tel: {{ $job_offer->company->primary_phone }},
                                                    {{ $job_offer->company->secondary_phone }}
                                                @else
                                                    Tel: {{ $job_offer->company->primary_phone }}
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
                                Hi {{ $candicate_name }},
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="margin: 16px 0 0 0; font-size: 13px">
                                We are pleased to inform you that you are appointed as {{ $candicate_position }} at
                                {{ $job_offer->department->name }} Department, {{ $job_offer?->company?->name }}.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td>
                            <p style="margin: 16px 0 0 0; font-size: 13px">
                                Yours sincerely,
                            </p>
                            <div style="margin-top: 8px; font-size: 13px">
                                <p style="margin: 4px 0; font-weight: 600">{{ $job_offer->approverPosition?->name }}
                                </p>
                                <p style="margin: 4px 0; font-weight: 600">
                                    {{ $job_offer->company->name }}
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
