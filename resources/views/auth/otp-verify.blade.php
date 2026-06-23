{{-- FILE PATH: resources/views/auth/otp-verify.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email — SWF PORTAL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
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
            max-width: 420px;
            overflow: hidden;
        }
        .otp-header {
            background: linear-gradient(135deg, #1e40af, #1d4ed8);
            padding: 32px 24px;
            text-align: center;
            color: #fff;
        }
        .otp-logo {
            width: 56px; height: 56px;
            background: rgba(255,255,255,0.2);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; font-weight: 900; color: #fff;
            margin: 0 auto 14px;
        }
        .otp-body { padding: 32px 28px; }

        /* OTP Input Boxes */
        .otp-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin: 24px 0;
        }
        .otp-box {
            width: 52px;
            height: 60px;
            border: 2.5px solid #e2e8f0;
            border-radius: 12px;
            text-align: center;
            font-size: 24px;
            font-weight: 800;
            color: #1e40af;
            outline: none;
            transition: all 0.2s;
            background: #f8fafc;
        }
        .otp-box:focus {
            border-color: #1e40af;
            background: #eff6ff;
            box-shadow: 0 0 0 4px rgba(30,64,175,0.12);
        }
        .otp-box.filled {
            border-color: #059669;
            background: #f0fdf4;
            color: #059669;
        }

        /* Timer */
        .timer-text { font-size: 13px; color: #64748b; }
        .timer-count { font-weight: 700; color: #1e40af; }
        .timer-count.expired { color: #dc2626; }

        /* Resend */
        .resend-btn {
            background: none; border: none; padding: 0;
            color: #1e40af; font-weight: 700; font-size: 14px;
            cursor: pointer; text-decoration: underline;
        }
        .resend-btn:disabled { color: #94a3b8; text-decoration: none; cursor: not-allowed; }

        .btn-verify {
            background: linear-gradient(135deg, #1e40af, #1d4ed8);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 700;
            font-size: 16px;
            padding: 14px;
            width: 100%;
            transition: all 0.2s;
        }
        .btn-verify:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(30,64,175,0.3); }
        .btn-verify:disabled { opacity: 0.6; transform: none; }

        /* Mobile adjustments */
        @media (max-width: 480px) {
            .otp-box { width: 44px; height: 54px; font-size: 20px; gap: 8px; }
            .otp-inputs { gap: 8px; }
            .otp-body { padding: 24px 20px; }
        }
    </style>
</head>
<body>

<div class="otp-card">
    {{-- Header --}}
    <div class="otp-header">
        <div class="otp-logo">SWF</div>
        <h4 class="fw-bold mb-1">Email Verification</h4>
        <p class="mb-0" style="opacity:0.8;font-size:13px;">SWF PORTAL — Student Welfare Foundation</p>
    </div>

    {{-- Body --}}
    <div class="otp-body">
        {{-- Alerts --}}
        @if(session('info'))
        <div class="alert alert-info d-flex align-items-center gap-2 py-2 mb-3" style="font-size:13px;border-radius:10px">
            <i class="bi bi-envelope-check"></i>
            <div>{{ session('info') }}</div>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger d-flex align-items-center gap-2 py-2 mb-3" style="font-size:13px;border-radius:10px">
            <i class="bi bi-exclamation-circle"></i>
            <div>{{ $errors->first() }}</div>
        </div>
        @endif

        <div class="text-center mb-2">
            <div style="font-size:40px">📧</div>
            <h5 class="fw-bold mt-2 mb-1">OTP Darj Karein</h5>
            <p class="text-muted" style="font-size:13px;line-height:1.6">
                Hum ne <strong>{{ session('otp_email') }}</strong> par<br>
                ek 6-digit verification code bheja hai.
            </p>
        </div>

        {{-- OTP Form --}}
        <form method="POST" action="{{ route('otp.verify') }}" id="otpForm">
            @csrf

            {{-- Hidden input for actual OTP value --}}
            <input type="hidden" name="otp" id="otpHidden">

            {{-- Visual OTP Boxes --}}
            <div class="otp-inputs">
                @for($i = 1; $i <= 6; $i++)
                <input type="text"
                       class="otp-box"
                       id="otp{{ $i }}"
                       maxlength="1"
                       inputmode="numeric"
                       pattern="[0-9]"
                       autocomplete="one-time-code"
                       {{ $i === 1 ? 'autofocus' : '' }}>
                @endfor
            </div>

            {{-- Timer --}}
            <div class="text-center mb-3">
                <span class="timer-text">Code expire hoga: </span>
                <span class="timer-count" id="timerDisplay">10:00</span>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-verify" id="submitBtn" disabled>
                <i class="bi bi-shield-check me-2"></i>Verify Email
            </button>
        </form>

        {{-- Resend --}}
        <div class="text-center mt-4">
            <p class="text-muted mb-1" style="font-size:13px">OTP nahi mila?</p>
            <form method="POST" action="{{ route('otp.resend') }}" id="resendForm">
                @csrf
                <button type="submit" class="resend-btn" id="resendBtn" disabled>
                    <i class="bi bi-arrow-clockwise me-1"></i>Dobara Bhejein
                </button>
            </form>
            <p class="text-muted mt-2" style="font-size:12px" id="resendTimer">
                30 seconds mein resend available hoga
            </p>
        </div>

        {{-- Back link --}}
        <div class="text-center mt-3">
            <a href="{{ route('student.login') }}" class="text-muted" style="font-size:13px">
                <i class="bi bi-arrow-left me-1"></i>Login page par waapas jayein
            </a>
        </div>
    </div>
</div>

<script>
"use strict";

/* ─── OTP Box Logic ──────────────────────────────────────── */
const boxes     = document.querySelectorAll('.otp-box');
const hidden    = document.getElementById('otpHidden');
const submitBtn = document.getElementById('submitBtn');

function updateHidden() {
    const val = [...boxes].map(b => b.value).join('');
    hidden.value = val;

    // Color each box
    boxes.forEach(b => {
        b.classList.toggle('filled', b.value.length === 1);
    });

    // Enable submit when all 6 filled
    submitBtn.disabled = val.length !== 6;
}

boxes.forEach((box, i) => {
    box.addEventListener('input', e => {
        // Only digits
        box.value = box.value.replace(/\D/, '').slice(-1);
        updateHidden();
        // Move to next
        if (box.value && i < boxes.length - 1) boxes[i + 1].focus();
    });

    box.addEventListener('keydown', e => {
        if (e.key === 'Backspace' && !box.value && i > 0) {
            boxes[i - 1].focus();
            boxes[i - 1].value = '';
            updateHidden();
        }
    });

    // Handle paste on first box
    box.addEventListener('paste', e => {
        e.preventDefault();
        const pasted = (e.clipboardData || window.clipboardData)
            .getData('text').replace(/\D/g, '').slice(0, 6);
        [...pasted].forEach((ch, idx) => {
            if (boxes[idx]) boxes[idx].value = ch;
        });
        updateHidden();
        const nextEmpty = [...boxes].find(b => !b.value);
        (nextEmpty || boxes[5]).focus();
    });
});

/* ─── OTP Countdown Timer (10 min) ──────────────────────── */
let secondsLeft  = 600;
const timerEl    = document.getElementById('timerDisplay');

const timerInterval = setInterval(() => {
    if (secondsLeft <= 0) {
        clearInterval(timerInterval);
        timerEl.textContent = 'Expire!';
        timerEl.classList.add('expired');
        submitBtn.disabled = true;
        return;
    }
    secondsLeft--;
    const m = Math.floor(secondsLeft / 60);
    const s = secondsLeft % 60;
    timerEl.textContent = `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
}, 1000);

/* ─── Resend Cooldown (30 sec) ───────────────────────────── */
const resendBtn   = document.getElementById('resendBtn');
const resendTimer = document.getElementById('resendTimer');
let resendLeft    = 30;

const resendInterval = setInterval(() => {
    if (resendLeft <= 0) {
        clearInterval(resendInterval);
        resendBtn.disabled    = false;
        resendTimer.textContent = '';
        return;
    }
    resendLeft--;
    resendTimer.textContent = `${resendLeft} seconds mein resend available hoga`;
}, 1000);

/* ─── Form submit anim ───────────────────────────────────── */
document.getElementById('otpForm').addEventListener('submit', () => {
    submitBtn.disabled   = true;
    submitBtn.innerHTML  = '<span class="spinner-border spinner-border-sm me-2"></span>Verify ho raha hai...';
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>