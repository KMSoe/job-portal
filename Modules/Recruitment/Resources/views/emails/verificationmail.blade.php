<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verify your email</title>
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
                                thanks for signing up — Use the code below confirm your email address.
                            </p>
                        </td>
                    </tr>

                    <tr style="margin: 32px 0 0 0;">
                        <td>
                            <h1 style="background-color:#d3d3d352;text-align:center"><a
                                    style="text-decoration:none;color:#1c1d1f">{{ $otp }}</a></h1>
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

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
