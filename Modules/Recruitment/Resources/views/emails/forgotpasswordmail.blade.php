<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Reset Your Password | SHIFANOVA</title>
  <style>
    @media only screen and (max-width: 600px) {
      .container { width: 100% !important; padding: 20px !important; }
      .hero { padding: 30px 18px !important; }
      .btn { display:block !important; width:100% !important; box-sizing:border-box; }
    }
    body { margin:0; padding:0; background:#f4f6f8; font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; color:#172033; }
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

  <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background:#f4f6f8; width:100%; min-width:100%;">
    <tr>
      <td align="center" style="padding:28px 16px;">
        <!-- Main Card -->
        <table role="presentation" cellpadding="0" cellspacing="0" width="600" class="container" style="width:600px; max-width:100%; background:#ffffff; border-radius:12px; box-shadow:0 6px 20px rgba(23,32,51,0.08); overflow:hidden;">
          
          <!-- Header -->
          <tr>
            <td style="padding:20px 24px; text-align:left; background:linear-gradient(90deg,#fbfbff,#ffffff);">
              <a href="/" class="brand gradient-text">SHIFANOVA</a>
            </td>
          </tr>

          <!-- Hero -->
          <tr>
            <td class="hero" style="padding:40px 44px; text-align:left;">
              <h1 style="margin:0 0 12px 0; font-size:22px; color:#0f1724; font-weight:600;">
                Reset your password
              </h1>

              <p style="margin:0 0 20px 0; font-size:15px; line-height:22px; color:#394b59;">
                Hi {{$name}},<br>
                We received a request to reset your password. Click the button below to set a new one.  
                This link will expire in 60 minutes for your security.
              </p>

              <!-- CTA Button -->
              <table role="presentation" cellpadding="0" cellspacing="0" style="margin:20px 0;">
                <tr>
                  <td align="center">
                    <a href="{{$reset_link}}" class="btn" style="background:#2563eb; color:#ffffff; padding:12px 22px; border-radius:10px; font-weight:600; display:inline-block; font-size:15px;">
                      Reset Password
                    </a>
                  </td>
                </tr>
              </table>

              <!-- Fallback link -->
              <p style="margin:18px 0 0 0; font-size:13px; line-height:20px; color:#6b7b87;">
                If the button doesn't work, copy and paste this link into your browser:
                <br>
                <a href="{{$reset_link}}" style="color:#1e64ff; word-break:break-all;">{{$reset_link}}</a>
              </p>

              <!-- Note -->
              <p style="margin:24px 0 0 0; font-size:13px; line-height:20px; color:#6b7b87;">
                If you didn’t request a password reset, please ignore this message or contact our support team.
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="padding:18px 24px; background:#fbfbff; text-align:left; font-size:13px; color:#8b97a6;">
              <div style="margin-bottom:6px;">Stay secure,</div>
              <div style="color:#a0adbb;">© <span id="year">2025</span> SHIFANOVA. All rights reserved.</div>
            </td>
          </tr>
        </table>

        <!-- Bottom note -->
        <table role="presentation" cellpadding="0" cellspacing="0" width="600" style="max-width:100%; margin-top:12px;">
          <tr>
            <td style="text-align:center; font-size:12px; color:#aab6c3;">
              You’re receiving this email because a password reset was requested for your SHIFANOVA account.
            </td>
          </tr>
        </table>

      </td>
    </tr>
  </table>
</body>
</html>
