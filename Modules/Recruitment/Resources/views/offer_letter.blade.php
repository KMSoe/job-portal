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
                                                    <img src="{{ $logo }}" alt="{{ $job_offer->company->name }}"
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

                    <!-- Body Top Section -->
                    <tr>
                        <td style="padding-top: 32px">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="font-size: 13px">
                                <tr>
                                    @if (($job_offer->template?->template_data['is_showed_offer_date'] ?? false) == true)
                                        <td width="50%">
                                            <p style="margin: 0"><strong>
                                                    {{ Carbon\Carbon::parse($job_offer->offer_date)->format('d-m-Y') }}
                                                </strong></p>
                                        </td>
                                    @endif
                                    @if (($job_offer->template?->template_data['is_showed_ref'] ?? false) == true)
                                        <td width="50%" align="right">
                                            <p style="margin: 0"><strong>Ref:</strong>
                                                {{ $job_offer->offer_letter_ref }}
                                            </p>
                                        </td>
                                    @endif
                                </tr>
                            </table>

                            <div style="margin-top: 16px; font-size: 13px">
                                <p style="margin: 4px 0">
                                    <strong>Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                                        {{ $candicate_name }}</strong>
                                </p>
                                <p style="margin: 4px 0">
                                    <strong>Position&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                                        {{ $candicate_position }}</strong>
                                </p>
                            </div>

                            @if (($job_offer->template?->template_data['is_showed_subject'] ?? false) == true)
                                <p style="margin: 4px 0 0 0; font-size: 13px">
                                    <strong>SUBJECT&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;{{ $job_offer->offer_letter_subject }}</strong>
                                </p>
                            @endif

                            <p style="margin: 16px 0 0 0; font-size: 13px; line-height: 1.6">
                                {{-- We are pleased to inform you that you are appointed as an
                  <strong> job_title </strong> at
                  <strong> department </strong> Department with effect from
                  <strong> offer_date </strong>. --}}
                                {{-- {{ $job_offer->offer_letter_content }} --}}
                            </p>
                        </td>
                    </tr>

                    <!-- Dynamic Content -->
                    <tr>
                        <td
                            style="
                  padding: 16px 0;
                  font-size: 13px;
                  line-height: 1.6;
                  color: #374151;
                ">
                            {{ $offer_letter_content }}
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td>
                            <p style="margin: 16px 0 0 0; font-size: 13px">
                                Yours sincerely,
                            </p>

                            <!-- Signature Area -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="margin-top: 32px; font-size: 13px">
                                <tr>
                                    <td width="50%" valign="top">
                                        <p style="margin: 0">
                                            <strong>Approved by</strong><br />
                                            {{ $job_offer->approver?->name }}
                                        </p>
                                    </td>
                                    <td width="50%" valign="top" align="right">
                                        <p style="margin: 0">
                                            <strong>Accepted By</strong><br />
                                            {{ $candicate_name }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="margin-top: 48px; font-size: 13px">
                                <tr>
                                    <td width="50%">
                                        <p style="margin: 0">_________________________</p>
                                    </td>
                                    <td width="50%" align="right">
                                        <p style="margin: 0">_________________________</p>
                                    </td>
                                </tr>
                            </table>

                            <div style="margin-top: 8px; font-size: 13px">
                                <p style="margin: 4px 0; font-weight: 600">{{ $job_offer->approverPosition?->name }}
                                </p>
                                <p style="margin: 4px 0; font-weight: 600">
                                    {{ $job_offer->company->name }}
                                </p>
                            </div>

                            <!-- Final Section -->
                            <div
                                style="
                    margin-top: 32px;
                    padding-top: 16px;
                    border-top: 1px solid #d1d5db;
                    font-size: 13px;
                  ">
                                <p style="margin: 4px 0">
                                    <strong>To:
                                        @foreach ($job_offer->informedDepartments as $department)
                                            {{ $department->name }}@if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                        Department(s)
                                    </strong>
                                </p>
                                <p style="margin: 4px 0">
                                    <strong>CC:
                                        @foreach ($job_offer->ccUsers as $user)
                                            {{ $user->name }}@if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </strong>
                                </p>
                                <p style="margin: 4px 0">
                                    <strong>BCC:
                                        @foreach ($job_offer->bccUsers as $user)
                                            {{ $user->name }}@if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </strong>
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
