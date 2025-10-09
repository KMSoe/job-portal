<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Letter - ROYAL MOTOR</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333333; margin: 0; padding: 0; background-color: #f4f4f4;">

    <div style="max-width: 750px; margin: 20px auto; background: #ffffff; border-top: 10px solid black; border-bottom: 10px solid black; box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);">

        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 20%; background-color: #e55039; padding: 20px 10px; text-align: center;">
                    <div style="width: 60px; height: 60px; margin: 0 auto 5px auto; border: 2px solid white; background-color: white;">
                        <span style="font-size: 10px; color: #333;">[LOGO]</span>
                    </div>
                    <p style="color: white; font-size: 10px; font-weight: bold; margin: 0;">ROYAL MOTOR</p>
                </td>

                <td style="width: 80%; padding: 20px 30px; vertical-align: middle; border-bottom: 1px solid black;">
                    <p style="font-size: 20px; font-weight: bold; margin-top: 0; margin-bottom: 5px; color: #333333;">ROYAL MOTOR COMPANY LIMITED</p>
                    <p style="font-size: 12px; margin: 0; color: #555555;">No.66/B, Ayarwon Road, 7 West Quarter, Thakata Township, Yangon, Myanmar.</p>
                    <p style="font-size: 12px; margin: 0; color: #555555;">Tel: +95-9-952039966, +95-9-952049966</p>
                </td>
            </tr>
        </table>
        
        <div style="padding: 30px;">
        
            <table style="width: 100%; margin-bottom: 20px; font-size: 14px;">
                <tr>
                    <td style="text-align: left; padding: 2px 0; width: 50%; font-weight: bold;">5th July, 2025</td>
                    <td style="text-align: right; padding: 2px 0; width: 50%;">Ref: RM/Dept;/HR (Appt)/2025</td>
                </tr>
            </table>

            <table style="width: 50%; margin-bottom: 20px; font-size: 14px;">
                <tr>
                    <td style="width: 30%; padding: 2px 0;">Name</td>
                    <td style="width: 5%; padding: 2px 0;">:</td>
                    <td style="width: 65%; padding: 2px 0; border-bottom: 1px dotted #ccc;">{{ $name }}</td>
                </tr>
                <tr>
                    <td style="padding: 2px 0;">Position</td>
                    <td style="padding: 2px 0;">:</td>
                    <td style="padding: 2px 0; border-bottom: 1px dotted #ccc;">{{ $position }}</td>
                </tr>
            </table>
            
            <div style="margin-bottom: 20px;">
                <table style="font-size: 15px; font-weight: bold;">
                    <tr>
                        <td style="padding-right: 15px;">SUBJECT</td>
                        <td style="padding-right: 15px;">:</td>
                        <td>APPOINTMENT LETTER</td>
                    </tr>
                </table>
            </div>
            
            <p style="font-size: 14px; margin-bottom: 15px;">
                We are pleased to inform you that you are appointed as an 
                <span style="font-weight: bold; border-bottom: 1px dotted #ccc;">{{ $position }}</span> 
                at 
                <span style="font-weight: bold; border-bottom: 1px dotted #ccc;">{{ $department }}</span> 
                Department with effect from 
                <span style="font-weight: bold; border-bottom: 1px dotted #ccc;">{{ $joined_date }}</span>.
            </p>

            <p style="font-size: 14px; margin-bottom: 15px;">
                Your salary will be <span style="font-weight: bold; border-bottom: 1px dotted #ccc;">{{ $basic_salary }}</span> per month, your hours of work will be Monday to Saturday, 
                <span style="font-weight: bold;">9:00 AM to 5:00 PM</span> and as a condition of employment, you will be required to serve a <span style="font-weight: bold;">3 months probationary period</span>. If further evaluation of performance is required, the probationary period will be extended from 3 to 6 months.
            </p>

            <p style="font-size: 14px; margin-bottom: 15px;">
                It is our hope that your career with Royal Motor Co., Ltd. will be rewarding and challenging. Please confirm your acceptance of this offer by signing the enclosed copy and returning it to Human Resources Department. We look forward to your continual support and wish you every success in your employment with Royal Motor Co., Ltd.
            </p>

            <p style="font-size: 14px; margin-bottom: 30px;">
                Thank you for your prompt attention to this request. If you have any questions or concerns, please feel free to contact our office on <span style="font-weight: bold;">09-798236943</span>.
            </p>

            <p style="font-size: 14px; margin-bottom: 50px;">Yours sincerely,</p>

            <table style="width: 100%; border-collapse: collapse; margin-top: 50px;">
                <tr style="vertical-align: top;">
                    <td style="width: 50%;">
                        <p style="font-size: 14px; margin-bottom: 5px;">Approved by</p>
                        <div style="border-bottom: 1px solid black; width: 70%; height: 20px;"></div>
                    </td>
                    <td style="width: 50%; text-align: right;">
                        <p style="font-size: 14px; margin-bottom: 5px;">Accepted By</p>
                        <div style="border-bottom: 1px solid black; width: 70%; height: 20px; margin-left: auto;"></div>
                    </td>
                </tr>
            </table>
            
            <div style="margin-top: 30px;">
                <p style="font-weight: bold; font-size: 14px; margin: 0;">HR Manager</p>
                <p style="font-size: 14px; margin: 0;">Royal Motor Group of Companies</p>
            </div>

            <div style="margin-top: 30px; font-size: 13px;">
                <p style="margin: 0;">To: <span style="font-weight: bold;">( Department )</span></p>
                <p style="margin: 0;">Cc: MD, COO</p>
            </div>
            
        </div>
        </div>
</body>
</html>