{{-- FILE PATH: resources/views/emails/payment-approved.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Approved — SWF Portal</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:40px 16px;">
    <tr><td align="center">
        <table width="560" cellpadding="0" cellspacing="0"
               style="max-width:560px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">

            {{-- Header --}}
            <tr>
                <td style="background:linear-gradient(135deg,#059669,#047857);padding:36px 40px;text-align:center;">
                    <div style="font-size:52px;line-height:1;margin-bottom:12px;">🎉</div>
                    <h1 style="margin:0 0 4px;color:#fff;font-size:22px;font-weight:800;">
                        Payment Approved!
                    </h1>
                    <p style="margin:0;color:rgba(255,255,255,0.75);font-size:13px;">
                        SWF Portal — Student Welfare Foundation
                    </p>
                </td>
            </tr>

            {{-- Body --}}
            <tr>
                <td style="padding:40px 40px 32px;">
                    <p style="margin:0 0 24px;color:#0f172a;font-size:15px;line-height:1.7;">
                        Hello <strong>{{ $payment->user->name }}</strong>,<br>
                        Great news! Your payment has been verified and your <strong style="color:#059669;">{{ $payment->plan->name }} Plan</strong> subscription is now <strong style="color:#059669;">active</strong>.
                        You can now access all features included in your plan.
                    </p>

                    {{-- Details --}}
                    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:24px;margin-bottom:24px;">
                        <h3 style="margin:0 0 16px;color:#065f46;font-size:15px;font-weight:700;">
                            📋 Subscription Details
                        </h3>
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="padding:8px 0;color:#374151;font-size:14px;border-bottom:1px solid #d1fae5;">Plan</td>
                                <td style="padding:8px 0;color:#065f46;font-size:14px;font-weight:700;text-align:right;border-bottom:1px solid #d1fae5;">
                                    {{ $payment->plan->name }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:8px 0;color:#374151;font-size:14px;border-bottom:1px solid #d1fae5;">Amount Paid</td>
                                <td style="padding:8px 0;color:#065f46;font-size:14px;font-weight:700;text-align:right;border-bottom:1px solid #d1fae5;">
                                    PKR {{ number_format($payment->amount) }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:8px 0;color:#374151;font-size:14px;border-bottom:1px solid #d1fae5;">Payment Method</td>
                                <td style="padding:8px 0;color:#065f46;font-size:14px;font-weight:700;text-align:right;border-bottom:1px solid #d1fae5;">
                                    {{ strtoupper($payment->method) }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:8px 0;color:#374151;font-size:14px;">Subscription Valid</td>
                                <td style="padding:8px 0;color:#065f46;font-size:14px;font-weight:700;text-align:right;">
                                    30 Days
                                </td>
                            </tr>
                        </table>
                    </div>

                    {{-- Plan-specific features --}}
                    @php
                        $planName = strtolower($payment->plan->name);
                        $features = match(true) {
                            str_contains($planName, 'basic') => [
                                'Up to 10 Tests per month',
                                'Up to 100 Students',
                                'MCQ Builder',
                                'Basic Timer & Auto-Submit',
                                'PDF Result Download',
                            ],
                            str_contains($planName, 'pro') => [
                                'Up to 220 Tests per month',
                                'Up to 250 Students',
                                'Advanced MCQ Builder',
                                'Anti-Cheat System',
                                'Question Bank',
                                'PDF Export',
                                'Negative Marking',
                                'Random Question & Option Order',
                            ],
                            str_contains($planName, 'max') => [
                                'Unlimited Tests',
                                'Unlimited Students',
                                'All Pro Features',
                                'Full Analytics Dashboard',
                                'Priority Support',
                                'Custom Branding',
                                'Bulk Question Import',
                                'PDF + CSV Export',
                                'Anti-Cheating Protection (Auto-Ban on Violation)',
                                'Dedicated Account Manager',
                            ],
                            default => ['All plan features are now available to you.'],
                        };
                    @endphp

                    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:20px;margin-bottom:24px;">
                        <h3 style="margin:0 0 12px;color:#1e40af;font-size:14px;font-weight:700;">
                            ✅ Your {{ $payment->plan->name }} Plan Features:
                        </h3>
                        @foreach($features as $feature)
                        <div style="display:flex;align-items:center;gap:8px;padding:4px 0;font-size:13px;color:#1e3a8a;">
                            <span style="color:#059669;font-weight:700;">✓</span>
                            <span>{{ $feature }}</span>
                        </div>
                        @endforeach
                    </div>

                    @if($payment->admin_note)
                    <div style="background:#fffbeb;border-left:4px solid #f59e0b;border-radius:0 8px 8px 0;padding:14px 18px;margin-bottom:24px;">
                        <p style="margin:0;color:#92400e;font-size:13px;line-height:1.6;">
                            <strong>Note from Admin:</strong> {{ $payment->admin_note }}
                        </p>
                    </div>
                    @endif

                    <div style="text-align:center;margin-top:8px;">
                        <a href="{{ config('app.url') }}/instructor/dashboard"
                           style="display:inline-block;background:linear-gradient(135deg,#059669,#047857);
                                  color:#fff;text-decoration:none;padding:14px 36px;
                                  border-radius:10px;font-weight:700;font-size:15px;">
                            Go to Dashboard →
                        </a>
                    </div>
                </td>
            </tr>

            {{-- Footer --}}
            <tr>
                <td style="background:#f8fafc;padding:20px 40px;border-top:1px solid #e2e8f0;text-align:center;">
                    <p style="margin:0;color:#94a3b8;font-size:12px;">
                        &copy; {{ date('Y') }} Student Welfare Foundation &mdash;
                        <a href="mailto:swfhelpers@gmail.com"
                           style="color:#059669;text-decoration:none;">swfhelpers@gmail.com</a>
                    </p>
                </td>
            </tr>

        </table>
    </td></tr>
</table>
</body>
</html>