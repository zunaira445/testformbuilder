{{-- FILE PATH: resources/views/auth/otp-verify.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email — SWF Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e40af 0%, #059669 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .otp-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 440px;
            overflow: hidden;
        }
        .otp-header {
            background: linear-gradient(135deg, #1e40af, #1d4ed8);
            padding: 32px 28px;
            text-align: center;
            color: #fff;
        }
        .brand-logo {
            width: 56px; height: 56px;
            background: rgba(255,255,255,0.2);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; font-weight: 900;
            margin: 0 auto 14px;
        }
        .otp-body { padding: 36px 32px; }

        .email-display {
            background: #eff6ff;
            border: 1.5px solid #bfdbfe;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: 14px;
            color: #1e40af;
            font-weight: 600;
            text-align: center;
            margin-bottom: 24px;
            word-break: break-all;
        }

        /* OTP Boxes */
        .otp-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 8px;
        }
        .otp-box {
            width: 54px;
            height: 62px;
            border: 2.5px solid #e2e8f0;
            border-radius: 12px;
            text-align: center;
            font-size: 26px;
            font-weight: 800;
            color: #1e40af;
            outline: none;
            transition: all 0.2s;
            background: #f8fafc;
            -moz-appearance: textfield;
        }
        .otp-box::-webkit-outer-spin-button,
        .otp-box::-webkit-inner-spin-button { -webkit-appearance: none; }
        .otp-box:focus {
            border-color: #1e40af;
            background: #eff6ff;
            box-shadow: 0 0 0 4px rgba(30,64,175,0.12);
            transform: translateY(-2px);
        }
        .otp-box.filled  { border-color: #059669; background: #f0fdf4; color: #059669; }
        .otp-box.error   { border-color: #dc2626; background: #fef2f2; animation: shake .3s; }

        @keyframes shake {
            0%,100%{ transform: translateX(0); }
            25%    { transform: translateX(-6px); }
            75%    { transform: translateX(6px); }
        }

        /* Timer */
        .timer-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 13px;
            color: #64748b;
            margin-bottom: 20px;
        }
        #timerDisplay { font-weight: 700; color: #1e40af; }
        #timerDisplay.warning  { color: #d97706; }
        #timerDisplay.expired  { color: #dc2626; }

        /* Submit Button */
        .btn-verify {
            background: linear-gradient(135deg, #1e40af, #1d4ed8);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            padding: 14px;
            width: 100%;
            transition: all 0.2s;
            cursor: pointer;
        }
        .btn-verify:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(30,64,175,0.3);
        }
        .btn-verify:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* Resend */
        .resend-section { text-align: center; margin-top: 20px; }
        .resend-section p { font-size: 13px; color: #64748b; margin-bottom: 8px; }
        .btn-resend {
            background: none; border: none; padding: 0;
            color: #1e40af; font-weight: 700; font-size: 14px;
            cursor: pointer; text-decoration: underline;
            transition: color .2s;
        }
        .btn-resend:hover:not(:disabled) { color: #1d4ed8; }
        .btn-resend:disabled { color: #94a3b8; text-decoration: none; cursor: not-allowed; }
        #resendCountdown { font-size: 12px; color: #94a3b8; display: block; margin-top: 4px; }

        .divider { border: none; border-top: 1px solid #f1f5f9; margin: 20px 0; }

        .back-link {
            display: block; text-align: center;
            color: #94a3b8; font-size: 13px;
            text-decoration: none;
            transition: color .2s;
        }
        .back-link:hover { color: #64748b; }

        .spam-note {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 12px;
            color: #92400e;
            margin-bottom: 16px;
        }

        @media (max-width: 480px) {
            .otp-box    { width: 44px; height: 54px; font-size: 22px; }
            .otp-inputs { gap: 7px; }
            .otp-body   { padding: 28px 20px; }
        }
        @media (max-width: 360px) {
            .otp-box    { width: 38px; height: 48px; font-size: 18px; }
            .otp-inputs { gap: 5px; }
        }
    </style>
</head>
<body>
<div class="otp-card">

    {{-- Header --}}
    <div class="otp-header">
        <div class="brand-logo">SWF</div>
        <h4 class="fw-bold mb-1" style="font-size:1.2rem">Verify Your Email</h4>
        <p style="opacity:.75;font-size:13px;margin:0">SWF Portal — Student Welfare Foundation</p>
    </div>

    {{-- Body --}}
    <div class="otp-body">

        {{-- Success/Info Alert --}}
        @if(session('info'))
        <div class="alert alert-info d-flex align-items-start gap-2 py-2 mb-4" style="font-size:13px;border-radius:10px;border:none;background:#eff6ff;color:#1e40af">
            <i class="bi bi-envelope-check mt-1 flex-shrink-0"></i>
            <span>{{ session('info') }}</span>
        </div>
        @endif

        {{-- Error Alert --}}
        @if($errors->any())
        <div class="alert alert-danger d-flex align-items-start gap-2 py-2 mb-4" style="font-size:13px;border-radius:10px;border:none">
            <i class="bi bi-exclamation-circle mt-1 flex-shrink-0"></i>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        {{-- Spam note --}}
        <div class="spam-note">
            <i class="bi bi-info-circle me-1"></i>
            <strong>Note:</strong> If you don't see the email in your inbox, please check your <strong>Spam / Junk</strong> folder. The code expires in <strong>5 minutes</strong>.
        </div>

        {{-- Instruction --}}
        <p style="font-size:14px;color:#475569;text-align:center;margin-bottom:14px;line-height:1.6">
            We sent a <strong>6-digit verification code</strong> to:
        </p>
        <div class="email-display">
            <i class="bi bi-envelope me-2"></i>{{ session('otp_email', 'your email') }}
        </div>

        {{-- OTP Form --}}
        <form method="POST" action="{{ route('otp.verify') }}" id="otpForm">
            @csrf
            <input type="hidden" name="otp" id="otpHidden">

            <div class="otp-inputs" id="otpInputs">
                @for($i = 1; $i <= 6; $i++)
                <input type="tel"
                       class="otp-box"
                       id="otp{{ $i }}"
                       maxlength="1"
                       inputmode="numeric"
                       pattern="[0-9]"
                       autocomplete="{{ $i === 1 ? 'one-time-code' : 'off' }}"
                       {{ $i === 1 ? 'autofocus' : '' }}>
                @endfor
            </div>

            {{-- Timer — 5 minutes --}}
            <div class="timer-wrap">
                <i class="bi bi-clock" style="font-size:12px"></i>
                <span>Code expires in</span>
                <span id="timerDisplay">05:00</span>
            </div>

            <button type="submit" class="btn-verify" id="submitBtn" disabled>
                <i class="bi bi-shield-check me-2"></i>Verify Email Address
            </button>
        </form>

        {{-- Resend --}}
        <div class="resend-section">
            <p>Didn't receive the code?</p>
            <form method="POST" action="{{ route('otp.resend') }}">
                @csrf
                <button type="submit" class="btn-resend" id="resendBtn" disabled>
                    <i class="bi bi-arrow-clockwise me-1"></i>Resend Code
                </button>
            </form>
            <span id="resendCountdown">Available in 30 seconds</span>
        </div>

        <hr class="divider">
        <a href="{{ route('student.login') }}" class="back-link">
            <i class="bi bi-arrow-left me-1"></i>Back to Login
        </a>
    </div>
</div>

<script>
"use strict";

/* ─── OTP Boxes Logic ────────────────────────────────────── */
const boxes     = [...document.querySelectorAll('.otp-box')];
const hidden    = document.getElementById('otpHidden');
const submitBtn = document.getElementById('submitBtn');

function collectOtp() {
    return boxes.map(b => b.value).join('');
}

function syncHidden() {
    const val = collectOtp();
    hidden.value = val;
    boxes.forEach(b => {
        b.classList.toggle('filled', b.value !== '');
        b.classList.remove('error');
    });
    submitBtn.disabled = val.length !== 6;
}

boxes.forEach((box, i) => {
    box.addEventListener('input', () => {
        box.value = box.value.replace(/\D/g, '').slice(-1);
        syncHidden();
        if (box.value && i < boxes.length - 1) boxes[i + 1].focus();
    });

    box.addEventListener('keydown', e => {
        if (e.key === 'Backspace') {
            if (!box.value && i > 0) {
                boxes[i - 1].value = '';
                boxes[i - 1].focus();
                syncHidden();
            }
        }
        if (e.key === 'ArrowLeft'  && i > 0) boxes[i - 1].focus();
        if (e.key === 'ArrowRight' && i < boxes.length - 1) boxes[i + 1].focus();
    });

    box.addEventListener('paste', e => {
        e.preventDefault();
        const text = (e.clipboardData || window.clipboardData)
            .getData('text').replace(/\D/g, '').slice(0, 6);
        [...text].forEach((ch, idx) => { if (boxes[idx]) boxes[idx].value = ch; });
        syncHidden();
        const next = boxes.find(b => !b.value) || boxes[5];
        next.focus();
    });
});

/* ─── Submit animation ───────────────────────────────────── */
document.getElementById('otpForm').addEventListener('submit', function(e) {
    if (collectOtp().length !== 6) { e.preventDefault(); return; }
    submitBtn.disabled  = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Verifying...';
});

/* ─── Countdown Timer (5 min = 300 seconds) ─────────────── */
let otpSeconds   = 300;
const timerEl    = document.getElementById('timerDisplay');

const otpTimer = setInterval(() => {
    if (otpSeconds <= 0) {
        clearInterval(otpTimer);
        timerEl.textContent  = 'Expired';
        timerEl.className    = 'expired';
        submitBtn.disabled   = true;
        submitBtn.textContent = 'Code Expired — Please Resend';
        return;
    }
    otpSeconds--;
    if (otpSeconds <= 60) timerEl.className = 'warning';
    const m = Math.floor(otpSeconds / 60);
    const s = otpSeconds % 60;
    timerEl.textContent = `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
}, 1000);

/* ─── Resend cooldown (30 sec) ───────────────────────────── */
let resendSec       = 30;
const resendBtn     = document.getElementById('resendBtn');
const resendCountEl = document.getElementById('resendCountdown');

const resendTimer = setInterval(() => {
    if (resendSec <= 0) {
        clearInterval(resendTimer);
        resendBtn.disabled       = false;
        resendCountEl.textContent = '';
        return;
    }
    resendSec--;
    resendCountEl.textContent = `Available in ${resendSec} second${resendSec !== 1 ? 's' : ''}`;
}, 1000);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>