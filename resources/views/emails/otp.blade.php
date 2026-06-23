{{-- FILE PATH: resources/views/emails/otp.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification — SWF Portal</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:'Inter',Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:40px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">

                {{-- Header --}}
                <tr>
                    <td style="background:linear-gradient(135deg,#1e40af,#1d4ed8);padding:32px 40px;text-align:center;">
                        <div style="display:inline-block;width:52px;height:52px;background:rgba(255,255,255,0.2);border-radius:12px;line-height:52px;font-size:18px;font-weight:900;color:#fff;text-align:center;">SWF</div>
                        <h1 style="margin:16px 0 4px;color:#fff;font-size:22px;font-weight:800;">SWF PORTAL</h1>
                        <p style="margin:0;color:rgba(255,255,255,0.75);font-size:13px;">Student Welfare Foundation</p>
                    </td>
                </tr>

                {{-- Body --}}
                <tr>
                    <td style="padding:40px;">
                        <h2 style="margin:0 0 8px;color:#0f172a;font-size:20px;font-weight:700;">Verify Your Email Address</h2>
                        <p style="margin:0 0 24px;color:#64748b;font-size:15px;line-height:1.6;">
                            Assalam-o-Alaikum <strong>{{ $userName }}</strong>! 👋<br>
                            Aapki registration ke liye neeche diya gaya <strong>6-digit OTP code</strong> use karein.
                        </p>

                        {{-- OTP Box --}}
                        <div style="background:linear-gradient(135deg,#eff6ff,#dbeafe);border:2px dashed #3b82f6;border-radius:14px;padding:28px;text-align:center;margin-bottom:28px;">
                            <p style="margin:0 0 8px;color:#1e40af;font-size:13px;font-weight:600;text-transform:uppercase;letter-spacing:2px;">Your Verification Code</p>
                            <div style="font-size:48px;font-weight:900;color:#1e40af;letter-spacing:10px;font-family:monospace;">{{ $otp }}</div>
                            <p style="margin:12px 0 0;color:#64748b;font-size:13px;">Yeh code <strong>10 minutes</strong> tak valid rahega.</p>
                        </div>

                        <div style="background:#fef3c7;border-left:4px solid #f59e0b;border-radius:8px;padding:14px 18px;margin-bottom:24px;">
                            <p style="margin:0;color:#92400e;font-size:13px;line-height:1.6;">
                                ⚠️ <strong>Important:</strong> Yeh code kisi ke saath share mat karein. SWF Portal team kabhi bhi aapse OTP nahi maangti.
                            </p>
                        </div>

                        <p style="margin:0;color:#94a3b8;font-size:12px;">
                            Agar aapne register nahi kiya toh is email ko ignore karein.
                        </p>
                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td style="background:#f8fafc;padding:20px 40px;border-top:1px solid #e2e8f0;text-align:center;">
                        <p style="margin:0;color:#94a3b8;font-size:12px;">
                            &copy; {{ date('Y') }} Student Welfare Foundation &mdash;
                            <a href="mailto:swfhelpers@gmail.com" style="color:#1e40af;text-decoration:none;">swfhelpers@gmail.com</a>
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>