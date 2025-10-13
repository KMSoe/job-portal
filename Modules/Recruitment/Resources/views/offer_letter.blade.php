<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Offer Letter - Royal Motor Company</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
            margin: 50px;
            font-size: 14px;
            color: #000;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .logo {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logo img {
            width: 80px;
            height: 80px;
        }

        .company-info {
            text-align: right;
        }

        .company-info h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .company-info p {
            margin: 2px 0;
            font-size: 12px;
        }

        .content {
            margin-top: 30px;
            line-height: 1.6;
        }

        .ref {
            text-align: right;
        }

        .signature-section {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }

        .signature-block {
            width: 45%;
        }

        .footer {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 10px;
            font-size: 12px;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="logo">
            <img src="logo.png" alt="Royal Motor Logo">
            <strong>ROYAL MOTOR</strong>
        </div>
        <div class="company-info">
            <h2>ROYAL MOTOR COMPANY LIMITED</h2>
            <p>No.66/B, Ayarwon Road, 7 West Quarter, Thaketa Township, Yangon, Myanmar.</p>
            <p>Tel: +95-9-952039966, +95-9-952049966</p>
        </div>
    </div>

    <div class="content">
        <p class="ref">Ref:  ref </p>
        <p> offer_date </p>

        <p><strong>Name</strong> :  applicant_name </p>
        <p><strong>Position</strong> :  job_title </p>
        <p><strong>SUBJECT</strong> :  subject </p>

        <p>We are pleased to inform you that you are appointed as an  job_title  at  department 
            Department with effect from  offer_date .</p>

        <p> content </p>

        <p>Yours sincerely,</p>

        <div class="signature-section">
            <div class="signature-block">
                <p><strong>Approved By</strong><br>
                     approver_name <br>
                    ___________________________<br>
                    HR Manager<br>
                    Royal Motor Group of Companies</p>
            </div>
            <div class="signature-block" style="text-align:right;">
                <p><strong>Accepted By</strong><br>
                     applicant_name <br>
                    ___________________________</p>
            </div>
        </div>

        <div class="footer">
            <p><strong>To:</strong> ( Department )<br>
                <strong>Cc:</strong> MD, COO
            </p>
        </div>
    </div>

</body>

</html>
