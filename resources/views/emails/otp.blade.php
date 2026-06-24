{{-- FILE PATH: resources/views/emails/otp.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification — SWF Portal</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:40px 16px;">
    <tr><td align="center">
        <table width="560" cellpadding="0" cellspacing="0"
               style="max-width:560px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">

            {{-- Header --}}
            <tr>
                <td style="background:linear-gradient(135deg,#1e40af,#1d4ed8);padding:36px 40px;text-align:center;">
                    <div style="width:56px;height:56px;background:rgba(255,255,255,0.18);border-radius:12px;
                                line-height:56px;font-size:16px;font-weight:900;color:#fff;
                                text-align:center;margin:0 auto 16px;display:inline-block;">SWF</div>
                    <h1 style="margin:0 0 4px;color:#fff;font-size:22px;font-weight:800;letter-spacing:-0.3px;">
                        SWF Portal
                    </h1>
                    <p style="margin:0;color:rgba(255,255,255,0.7);font-size:13px;">
                        Student Welfare Foundation
                    </p>
                </td>
            </tr>

            {{-- Body --}}
            <tr>
                <td style="padding:40px 40px 32px;">
                    <h2 style="margin:0 0 6px;color:#0f172a;font-size:20px;font-weight:700;">
                        Verify Your Email Address
                    </h2>
                    <p style="margin:0 0 28px;color:#64748b;font-size:15px;line-height:1.65;">
                        Hello <strong style="color:#0f172a;">{{ $userName }}</strong>,<br>
                        Thank you for registering on SWF Portal. Use the verification code below to confirm your email address.
                    </p>

                    {{-- OTP Code Box --}}
                    <div style="background:linear-gradient(135deg,#eff6ff,#dbeafe);
                                border:2px dashed #3b82f6;border-radius:14px;
                                padding:28px 20px;text-align:center;margin-bottom:28px;">
                        <p style="margin:0 0 10px;color:#1e40af;font-size:12px;font-weight:700;
                                  text-transform:uppercase;letter-spacing:2.5px;">
                            Your Verification Code
                        </p>
                        <div style="font-size:52px;font-weight:900;color:#1e40af;
                                    letter-spacing:14px;font-family:monospace;line-height:1;">
                            {{ $otp }}
                        </div>
                        <p style="margin:14px 0 0;color:#64748b;font-size:13px;">
                            This code is valid for <strong>10 minutes</strong>.
                        </p>
                    </div>

                    {{-- Warning --}}
                    <div style="background:#fef3c7;border-left:4px solid #f59e0b;
                                border-radius:0 8px 8px 0;padding:14px 18px;margin-bottom:24px;">
                        <p style="margin:0;color:#92400e;font-size:13px;line-height:1.65;">
                            <strong>⚠️ Security Notice:</strong> Never share this code with anyone.
                            SWF Portal staff will never ask for your OTP.
                        </p>
                    </div>

                    <p style="margin:0;color:#94a3b8;font-size:13px;">
                        If you did not create an account, you can safely ignore this email.
                    </p>
                </td>
            </tr>

            {{-- Footer --}}
            <tr>
                <td style="background:#f8fafc;padding:20px 40px;
                            border-top:1px solid #e2e8f0;text-align:center;">
                    <p style="margin:0;color:#94a3b8;font-size:12px;">
                        &copy; {{ date('Y') }} Student Welfare Foundation &mdash;
                        <a href="mailto:swfhelpers@gmail.com"
                           style="color:#1e40af;text-decoration:none;">swfhelpers@gmail.com</a>
                    </p>
                </td>
            </tr>

        </table>
    </td></tr>
</table>
</body>
</html>