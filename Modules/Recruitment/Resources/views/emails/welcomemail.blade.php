<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome to {{ env('APP_NAME') }}</title>
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
                                                    <h1
                                                        style="
                            margin: 0 0 8px 0;
                            font-weight: bold;
                            font-size: 20px;
                            color: #000000;
                            text-transform: uppercase;
                            letter-spacing: 0.05em;
                          ">
                                                        {{ env('APP_NAME') }}
                                                    </h1>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>

                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <p style="margin: 16px 0 0 0; font-size: 13px">
                                Hi {{ $name }},
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="margin: 16px 0 0 0; font-size: 13px">
                                You are officially part of the {{ env('APP_NAME') }} family!
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <p style="margin: 16px 0 0 0; font-size: 13px">
                                We're excited to have you join our community!
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <p style="margin: 16px 0 0 0; font-size: 13px">
                                Get ready to explore new Job Opportunities!
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0;">
                            <div class="me-email-browse-product">
                                <a href="{{ env('FRONTEND_URL') }}"
                                    style="
       font-size: 16px;
       font-weight: bold;
       color: #ffffff; /* White text for contrast */
       background-color: #007bff; /* A standard, professional blue */
       border: 1px solid #007bff; /* Blue border to match background */
       border-radius: 5px; /* Added border radius for rounded corners */
       padding: 10px 20px; /* Added vertical and horizontal padding */
       display: inline-block; /* Essential for padding/sizing */
       text-decoration: none; /* Remove underline */
       margin-top: 16px;
   ">
                                    Go To Website
                                </a>
                            </div>
                        </td>
                    </tr>


                    <tr>
                        <td style="padding-top: 30px;">
                            <table class="no-padding-table" bgColor="white" align="left" valign="middle"
                                border="0" style="width: 100%;">
                                <tr>
                                    <td style="padding:0;">
                                        <p
                                            style="margin-top: 0px; font-size: 1rem;line-height: normal;
                                    font-weight: normal;
                                    color: #1B3757;">
                                            <span style="display:block;margin-bottom: 2px;">Regards,</span><b>
                                                {{ Config::get('app.name') }}</b>
                                        </p>
                                        <hr style="margin: 32px 0;background-color: #DADBE4;width: 100%;">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="no-padding-table" bgColor="white" align="left" valign="middle"
                                border="0" style="width: 100%;border-radius: 12px;">
                                <tr>
                                    <td style="padding-left:36px;padding-right: 36px;padding-bottom: 36px;">
                                        <p
                                            style="margin-top: 0px; font-size: 12px;line-height: normal;font-style: italic;
                                    font-weight: normal;
                                    color: #363848;">
                                            If you're having trouble clicking the button, copy and paste the URL below
                                            into your web browser:<a href="{{ env('FRONTEND_URL') }}"
                                                style="white-space: normal;word-break: break-all;text-decoration: underline;color: #363848;">{{ env('FRONTEND_URL') }}</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
