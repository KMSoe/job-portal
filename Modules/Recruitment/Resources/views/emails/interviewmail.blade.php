<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Interview Invitation</title>
    <style>
        /* Desktop styles — many clients ignore these, but some use them */
        @media only screen and (max-width: 600px) {
        .container { width: 100% !important; padding: 20px !important; }
        .hero { padding: 30px 18px !important; }
        .btn { display:block !important; width:100% !important; box-sizing:border-box; }
        }
        /* A little reset */
        body { margin:0; padding:0; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; }
        a { color: inherit; text-decoration: none; }

        .brand {
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 28px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-decoration: none;
        }

        .gradient-text {
            background: linear-gradient(135deg, hsl(217 91% 60%) 0%, hsl(199 89% 48%) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body style="margin:0; padding:0; background:#f4f6f8; font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; color:#172033;">
  <!-- Centering wrapper -->
  <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background:#f4f6f8; width:100%; min-width:100%;">
    <tr>
      <td align="center" style="padding:28px 16px;">
        <!-- Card -->
        <table role="presentation" cellpadding="0" cellspacing="0" width="600" class="container" style="width:600px; max-width:100%; background:#ffffff; border-radius:12px; box-shadow:0 6px 20px rgba(23,32,51,0.08); overflow:hidden;">
          <!-- Header with logo -->
          <tr>
            <td style="padding:20px 24px; text-align:left; background:linear-gradient(90deg,#fbfbff,#ffffff);">
              <a href="/" class="brand gradient-text"><img src="{{ $message->embedData($interview->application->jobPosting->company->logo, 'logo.png') }}" alt="LOGO" style="max-width: 100%; height: auto;"></a>
            </td>
          </tr>

          <tr>
            <td class="hero" style="padding:36px 40px; text-align:left;">
              <h1 style="margin:0 0 12px 0; font-size:20px; line-height:28px; color:#0f1724; font-weight:600;">
                Interview Invitation
              </h1>

              <p style="margin:0 0 20px 0; font-size:15px; line-height:22px; color:#394b59;">
                Dear {{$interview->application->applicant->name}},
              </p>

              <p style="margin:0 0 20px 0; font-size:15px; line-height:22px; color:#394b59;">
                I hope this email finds you well. We were impressed by your background and would like to invite you to interview for the {{$interview->application->jobPosting->title}} role at {{$interview->application->jobPosting->company->name}}. We believe your skills and experiences align well with the responsibilities of the position.
              </p>

              <p style="margin:0 0 12px 0; font-size:15px; line-height:22px; color:#394b59;">
                <strong>Interview Details:</strong><br />
                Date: {{$interview->scheduled_at->format('F j, Y')}}<br />
                Time: {{$interview->scheduled_at->format('g:i A')}}<br />
                Location: {{$interview->location}}
              </p>

              <p style="margin:0 0 20px 0; font-size:15px; line-height:22px; color:#394b59;">
                Please let us know if this time works for you or if there are any conflicts. We look forward to the opportunity to discuss your potential fit with our team.
              </p>

              <p style="margin:0; font-size:15px; line-height:22px; color:#394b59;">
                Best regards,<br />
                {{ $user->name }}<br />
                {{ $user->employee?->position ?? 'Recruiter' }}
              </p>
            </td>
          </tr>


          <!-- Footer -->
          <tr>
            <td style="padding:18px 24px; background:#fbfbff; text-align:left; font-size:13px; color:#8b97a6;">
              <div style="margin-bottom:8px;">Need help? Reply to this email and we'll get back to you.</div>
              <div style="color:#a0adbb;">© <span id="year">2025</span> SHIFANOVA. All rights reserved.</div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>






