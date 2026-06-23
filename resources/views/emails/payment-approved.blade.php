{{-- FILE PATH: resources/views/emails/payment-approved.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Approved — SWF Portal</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:'Inter',Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:40px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">

                {{-- Header --}}
                <tr>
                    <td style="background:linear-gradient(135deg,#059669,#047857);padding:32px 40px;text-align:center;">
                        <div style="font-size:48px;">🎉</div>
                        <h1 style="margin:12px 0 4px;color:#fff;font-size:22px;font-weight:800;">Payment Approved!</h1>
                        <p style="margin:0;color:rgba(255,255,255,0.8);font-size:13px;">SWF Portal — Student Welfare Foundation</p>
                    </td>
                </tr>

                {{-- Body --}}
                <tr>
                    <td style="padding:40px;">
                        <p style="margin:0 0 20px;color:#0f172a;font-size:15px;line-height:1.7;">
                            Assalam-o-Alaikum <strong>{{ $payment->user->name }}</strong>! 👋<br>
                            Mubarak ho! Aapki payment verify ho gayi hai aur aapka subscription activate kar diya gaya hai.
                        </p>

                        {{-- Details Box --}}
                        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:24px;margin-bottom:24px;">
                            <h3 style="margin:0 0 16px;color:#065f46;font-size:15px;font-weight:700;">📋 Subscription Details</h3>
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding:6px 0;color:#374151;font-size:14px;border-bottom:1px solid #d1fae5;">Plan</td>
                                    <td style="padding:6px 0;color:#065f46;font-size:14px;font-weight:700;text-align:right;border-bottom:1px solid #d1fae5;">{{ $payment->plan->name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;color:#374151;font-size:14px;border-bottom:1px solid #d1fae5;">Amount Paid</td>
                                    <td style="padding:6px 0;color:#065f46;font-size:14px;font-weight:700;text-align:right;border-bottom:1px solid #d1fae5;">PKR {{ number_format($payment->amount) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;color:#374151;font-size:14px;border-bottom:1px solid #d1fae5;">Payment Method</td>
                                    <td style="padding:6px 0;color:#065f46;font-size:14px;font-weight:700;text-align:right;border-bottom:1px solid #d1fae5;">{{ strtoupper($payment->method) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;color:#374151;font-size:14px;">Valid Until</td>
                                    <td style="padding:6px 0;color:#065f46;font-size:14px;font-weight:700;text-align:right;">30 Days from today</td>
                                </tr>
                            </table>
                        </div>

                        @if($payment->admin_note)
                        <div style="background:#fffbeb;border-left:4px solid #f59e0b;border-radius:8px;padding:14px 18px;margin-bottom:24px;">
                            <p style="margin:0;color:#92400e;font-size:13px;line-height:1.6;">
                                <strong>Admin Note:</strong> {{ $payment->admin_note }}
                            </p>
                        </div>
                        @endif

                        <div style="text-align:center;margin-top:20px;">
                            <a href="{{ config('app.url') }}/instructor/dashboard"
                               style="display:inline-block;background:linear-gradient(135deg,#059669,#047857);color:#fff;text-decoration:none;padding:14px 32px;border-radius:10px;font-weight:700;font-size:15px;">
                                🚀 Go to Dashboard
                            </a>
                        </div>
                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td style="background:#f8fafc;padding:20px 40px;border-top:1px solid #e2e8f0;text-align:center;">
                        <p style="margin:0;color:#94a3b8;font-size:12px;">
                            &copy; {{ date('Y') }} Student Welfare Foundation &mdash;
                            <a href="mailto:swfhelpers@gmail.com" style="color:#059669;text-decoration:none;">swfhelpers@gmail.com</a>
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>