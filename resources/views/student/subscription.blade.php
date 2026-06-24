{{--
====================================================================
FILE PATH: resources/views/student/subscription.blade.php
====================================================================
--}}
@extends('layouts.app')
@section('title', 'Upgrade Your Plan')

@push('styles')
<style>
/* ─── PLAN CARDS ─────────────────────────────────────────── */
.plan-card {
    border-radius: 20px;
    border: 2px solid var(--bs-border-color);
    transition: transform .3s, box-shadow .3s, border-color .3s;
    overflow: hidden;
    position: relative;
}
.plan-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 50px rgba(0,0,0,.12);
}
.plan-card.popular {
    border-color: #1e40af;
    transform: scale(1.04);
    box-shadow: 0 12px 40px rgba(30,64,175,.2);
    z-index: 2;
}
.plan-card.popular:hover { transform: scale(1.04) translateY(-6px); }

.popular-ribbon {
    position: absolute;
    top: 0; right: 0;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
    font-size: .7rem;
    font-weight: 800;
    padding: .3rem .9rem;
    border-bottom-left-radius: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.plan-header { padding: 1.75rem 1.75rem 1rem; }
.plan-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 1rem;
}
.plan-name { font-size: 1.1rem; font-weight: 800; margin-bottom: .25rem; }
.plan-tagline { font-size: .82rem; color: var(--bs-secondary-color); }

.plan-pricing { padding: 0 1.75rem 1.25rem; }
.billing-toggle-wrap { display: flex; gap: .5rem; margin-bottom: .75rem; }
.price-box {
    background: var(--bs-secondary-bg);
    border-radius: 12px;
    padding: .6rem .9rem;
    flex: 1;
    text-align: center;
    border: 1.5px solid transparent;
    cursor: pointer;
    transition: all .2s;
}
.price-box.active { border-color: #1e40af; background: rgba(30,64,175,.08); }
.price-box .price-amount { font-size: 1.3rem; font-weight: 800; }
.price-box .price-period { font-size: .68rem; color: var(--bs-secondary-color); }
.price-box .price-savings { font-size: .7rem; color: #059669; font-weight: 700; margin-top: .1rem; }
.price-box .price-original { font-size: .75rem; color: var(--bs-secondary-color); text-decoration: line-through; }

.plan-features { padding: 0 1.75rem 1.75rem; }
.feature-item {
    display: flex;
    align-items: flex-start;
    gap: .6rem;
    padding: .35rem 0;
    font-size: .875rem;
}
.feature-check { color: #059669; font-size: .9rem; flex-shrink: 0; margin-top: .05rem; }
.feature-cross  { color: #9ca3af; font-size: .9rem; flex-shrink: 0; margin-top: .05rem; }
.feature-cross ~ span { color: var(--bs-secondary-color); }

.plan-cta {
    padding: 0 1.75rem 1.75rem;
    display: grid;
}
.btn-plan {
    border-radius: 12px;
    padding: .75rem;
    font-weight: 700;
    font-size: .95rem;
    transition: all .2s;
}
.btn-plan:hover { transform: translateY(-2px); }

/* ─── CURRENT PLAN BADGE ─────────────────────────────────── */
.current-plan-badge {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: #059669;
    color: #fff;
    text-align: center;
    font-size: .75rem;
    font-weight: 700;
    padding: .3rem;
}

/* ─── PAYMENT SECTION ────────────────────────────────────── */
.payment-method-card {
    border: 2px solid var(--bs-border-color);
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: border-color .2s, background .2s;
    cursor: default;
}
.payment-method-card:hover { border-color: #3b82f6; background: rgba(59,130,246,.04); }
.payment-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    flex-shrink: 0;
}
.payment-detail-label { font-size: .7rem; text-transform: uppercase; letter-spacing: .8px; color: var(--bs-secondary-color); font-weight: 600; }
.payment-detail-val   { font-weight: 700; font-size: .95rem; }
.copy-badge {
    font-size: .7rem;
    padding: .15rem .5rem;
    border-radius: 4px;
    cursor: pointer;
    background: var(--bs-secondary-bg);
    border: 1px solid var(--bs-border-color);
    transition: all .15s;
}
.copy-badge:hover { background: #1e40af; color: #fff; border-color: #1e40af; }

/* ─── UPLOAD FORM ─────────────────────────────────────────── */
.upload-zone {
    border: 2.5px dashed var(--bs-border-color);
    border-radius: 14px;
    padding: 2rem;
    text-align: center;
    transition: all .2s;
    cursor: pointer;
    background: var(--bs-secondary-bg);
}
.upload-zone:hover, .upload-zone.dragging {
    border-color: #3b82f6;
    background: rgba(59,130,246,.05);
}
.upload-zone i { font-size: 2.5rem; color: var(--bs-secondary-color); }
.upload-preview img {
    max-height: 200px;
    border-radius: 10px;
    box-shadow: 0 4px 16px rgba(0,0,0,.1);
}

/* ─── FAQ ACCORDION ──────────────────────────────────────── */
.faq-item { border-radius: 10px; overflow: hidden; margin-bottom: .5rem; }
.faq-item .accordion-button { font-weight: 600; font-size: .9rem; }
.faq-item .accordion-button:not(.collapsed) { background: rgba(30,64,175,.07); color: #1e40af; }

/* ─── PLAN SELECTED HIGHLIGHT ────────────────────────────── */
#planSelect option:checked { background: #dbeafe; }
.plan-selected-badge {
    display: inline-block;
    background: linear-gradient(135deg,#059669,#047857);
    color: #fff;
    border-radius: 20px;
    padding: .25rem .75rem;
    font-size: .78rem;
    font-weight: 700;
    margin-left: .5rem;
}
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════════════════════ --}}
<div class="text-center mb-5">
    <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold px-3 py-2 mb-3" style="border-radius:20px">
        <i class="bi bi-gem me-1"></i>Upgrade Your Account
    </span>
    <h2 class="fw-bold display-6 mb-2">Choose Your Plan</h2>
    <p class="text-muted mb-0" style="max-width:520px;margin:0 auto">
        Unlock more tests, students, and powerful features to supercharge your exam experience on SWF Portal.
    </p>
    @if($sub)
    <div class="alert alert-success d-inline-flex align-items-center gap-2 mt-3 px-4 py-2" style="border-radius:20px">
        <i class="bi bi-check-circle-fill"></i>
        You're on <strong>{{ $sub->plan->name }}</strong> — expires {{ $sub->expires_at->format('d M Y') }}
    </div>
    @endif
</div>

{{-- ═══════════════════════════════════════════════════════
     PRICING CARDS  (3 tiers)
══════════════════════════════════════════════════════════ --}}
<div class="row g-4 justify-content-center mb-5 align-items-stretch">

    {{-- ─── BASIC ───────────────────────────────── --}}
    <div class="col-md-4 col-lg-3">
        <div class="plan-card h-100 shadow-sm {{ $sub?->plan?->name === 'Basic' ? 'border-success' : '' }}">
            @if($sub?->plan?->name === 'Basic')<div class="current-plan-badge">✓ Current Plan</div>@endif
            <div class="plan-header">
                <div class="plan-icon bg-secondary bg-opacity-10 text-secondary"><i class="bi bi-box"></i></div>
                <div class="plan-name">Basic</div>
                <div class="plan-tagline">Perfect for individual tutors getting started</div>
            </div>
            <div class="plan-pricing">
                <div class="billing-toggle-wrap">
                    <div class="price-box active" id="basicMonthly" onclick="selectBilling('basic','monthly')">
                        <div class="price-amount text-body">PKR 2,000</div>
                        <div class="price-period">per month</div>
                    </div>
                    <div class="price-box" id="basicYearly" onclick="selectBilling('basic','yearly')">
                        <div class="price-original">PKR 24,000</div>
                        <div class="price-amount text-success">PKR 12,000</div>
                        <div class="price-period">per year</div>
                        <div class="price-savings">Save 50%! 🎉</div>
                    </div>
                </div>
            </div>
            <div class="plan-features">
                @foreach([
                    [true,'Up to 10 Tests per month'],
                    [true,'Up to 100 Students'],
                    [true,'MCQ Builder'],
                    [true,'Basic Timer & Auto-Submit'],
                    [true,'PDF Result Download'],
                    [false,'Anti-Cheat System'],
                    [false,'Question Bank'],
                    [false,'Excel / CSV Export'],
                    [false,'Analytics Dashboard'],
                    [false,'Negative Marking'],
                ] as [$has,$feat])
                <div class="feature-item">
                    <i class="bi {{ $has ? 'bi-check-circle-fill feature-check' : 'bi-x-circle feature-cross' }}"></i>
                    <span>{{ $feat }}</span>
                </div>
                @endforeach
            </div>
            <div class="plan-cta">
                <button class="btn btn-plan btn-outline-secondary" onclick="openPaymentForm('Basic', 2000, 'monthly')">
                    Get Basic
                </button>
            </div>
        </div>
    </div>

    {{-- ─── PRO (POPULAR) — UPDATED ─────────────── --}}
    <div class="col-md-4 col-lg-3">
        <div class="plan-card popular h-100 shadow {{ $sub?->plan?->name === 'Pro' ? 'border-success' : '' }}">
            <div class="popular-ribbon">⭐ Most Popular</div>
            @if($sub?->plan?->name === 'Pro')<div class="current-plan-badge">✓ Current Plan</div>@endif
            <div class="plan-header">
                <div class="plan-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-rocket-takeoff"></i></div>
                <div class="plan-name text-primary">Pro</div>
                <div class="plan-tagline">Ideal for schools and coaching centres</div>
            </div>
            <div class="plan-pricing">
                <div class="billing-toggle-wrap">
                    <div class="price-box active" id="proMonthly" onclick="selectBilling('pro','monthly')">
                        <div class="price-amount text-primary">PKR 3,500</div>
                        <div class="price-period">per month</div>
                    </div>
                    <div class="price-box" id="proYearly" onclick="selectBilling('pro','yearly')">
                        <div class="price-original">PKR 42,000</div>
                        <div class="price-amount text-success">PKR 20,000</div>
                        <div class="price-period">per year</div>
                        <div class="price-savings">Save 52%! 🎉</div>
                    </div>
                </div>
            </div>
            <div class="plan-features">
                @foreach([
                    [true,'Up to 220 Tests per month'],
                    [true,'Up to 250 Students'],
                    [true,'Advanced MCQ Builder'],
                    [true,'Anti-Cheat System'],
                    [true,'Question Bank'],
                    [true,'PDF Export'],
                    [true,'Negative Marking'],
                    [true,'Random Q & Option Order'],
                    [false,'Analytics Dashboard'],
                    [false,'Unlimited Tests'],
                ] as [$has,$feat])
                <div class="feature-item">
                    <i class="bi {{ $has ? 'bi-check-circle-fill feature-check' : 'bi-x-circle feature-cross' }}"></i>
                    <span>{{ $feat }}</span>
                </div>
                @endforeach
            </div>
            <div class="plan-cta">
                <button class="btn btn-plan btn-primary" onclick="openPaymentForm('Pro', 3500, 'monthly')">
                    <i class="bi bi-rocket-takeoff me-1"></i>Get Pro
                </button>
            </div>
        </div>
    </div>

    {{-- ─── MAX — UPDATED ──────────────────────── --}}
    <div class="col-md-4 col-lg-3">
        <div class="plan-card h-100 shadow-sm {{ $sub?->plan?->name === 'Max' ? 'border-success' : '' }}" style="border-color:#7c3aed">
            @if($sub?->plan?->name === 'Max')<div class="current-plan-badge">✓ Current Plan</div>@endif
            <div class="plan-header">
                <div class="plan-icon" style="background:rgba(124,58,237,.12);color:#7c3aed"><i class="bi bi-trophy"></i></div>
                <div class="plan-name" style="color:#7c3aed">Max</div>
                <div class="plan-tagline">For universities and large institutions</div>
            </div>
            <div class="plan-pricing">
                <div class="billing-toggle-wrap">
                    <div class="price-box active" id="maxMonthly" onclick="selectBilling('max','monthly')" style="border-color:#7c3aed;background:rgba(124,58,237,.06)">
                        <div class="price-amount" style="color:#7c3aed">PKR 7,000</div>
                        <div class="price-period">per month</div>
                    </div>
                    <div class="price-box" id="maxYearly" onclick="selectBilling('max','yearly')">
                        <div class="price-original">PKR 84,000</div>
                        <div class="price-amount text-success">PKR 40,000</div>
                        <div class="price-period">per year</div>
                        <div class="price-savings">Save 52%! 🎉</div>
                    </div>
                </div>
            </div>
            <div class="plan-features">
                @foreach([
                    [true,'Unlimited Tests'],
                    [true,'Unlimited Students'],
                    [true,'All Pro Features'],
                    [true,'Full Analytics Dashboard'],
                    [true,'Priority Support'],
                    [true,'Custom Branding'],
                    [true,'Bulk Question Import'],
                    [true,'PDF + CSV Export'],
                    [true,'Anti-Cheating Protection (Auto-Ban on Violation)'],
                    [true,'Dedicated Account Manager'],
                ] as [$has,$feat])
                <div class="feature-item">
                    <i class="bi {{ $has ? 'bi-check-circle-fill feature-check' : 'bi-x-circle feature-cross' }}"></i>
                    <span>{{ $feat }}</span>
                </div>
                @endforeach
            </div>
            <div class="plan-cta">
                <button class="btn btn-plan fw-bold" style="background:linear-gradient(135deg,#7c3aed,#5b21b6);color:#fff" onclick="openPaymentForm('Max', 7000, 'monthly')">
                    <i class="bi bi-trophy me-1"></i>Get Max
                </button>
            </div>
        </div>
    </div>

</div>{{-- /row plans --}}

{{-- ═══════════════════════════════════════════════════════
     PAYMENT METHODS DISPLAY
══════════════════════════════════════════════════════════ --}}
<div class="mb-5">
    <div class="text-center mb-4">
        <h4 class="fw-bold">How to Pay</h4>
        <p class="text-muted small">Send payment to any of the following accounts, then upload your payment proof below.</p>
    </div>

    <div class="row g-3 justify-content-center">
        {{-- JazzCash --}}
        <div class="col-md-4">
            <div class="payment-method-card h-100">
                <div class="payment-icon" style="background:linear-gradient(135deg,#e4003a,#c0002e);color:#fff">
                    <i class="bi bi-phone-fill"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold mb-1" style="font-size:1rem">JazzCash</div>
                    <div>
                        <div class="payment-detail-label">Account Title</div>
                        <div class="payment-detail-val">Nisar Ahmed</div>
                    </div>
                    <div class="mt-1 d-flex align-items-center gap-2">
                        <div>
                            <div class="payment-detail-label">Mobile Number</div>
                            <div class="payment-detail-val">0322-7653486</div>
                        </div>
                        <span class="copy-badge ms-auto" onclick="copyText('03227653486','jc')">
                            <i class="bi bi-copy me-1"></i><span id="jc">Copy</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- EasyPaisa --}}
        <div class="col-md-4">
            <div class="payment-method-card h-100">
                <div class="payment-icon" style="background:linear-gradient(135deg,#00a651,#007a3c);color:#fff">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold mb-1" style="font-size:1rem">EasyPaisa</div>
                    <div>
                        <div class="payment-detail-label">Account Title</div>
                        <div class="payment-detail-val">Nisar Ahmed</div>
                    </div>
                    <div class="mt-1 d-flex align-items-center gap-2">
                        <div>
                            <div class="payment-detail-label">Mobile Number</div>
                            <div class="payment-detail-val">0322-7653486</div>
                        </div>
                        <span class="copy-badge ms-auto" onclick="copyText('03227653486','ep')">
                            <i class="bi bi-copy me-1"></i><span id="ep">Copy</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Binance Pay --}}
        <div class="col-md-4">
            <div class="payment-method-card h-100">
                <div class="payment-icon" style="background:linear-gradient(135deg,#f0b90b,#d4a017);color:#000">
                    <i class="bi bi-currency-bitcoin"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold mb-1" style="font-size:1rem">Binance Pay</div>
                    <div>
                        <div class="payment-detail-label">Account Title</div>
                        <div class="payment-detail-val">Katyar Traders 09</div>
                    </div>
                    <div class="mt-1 d-flex align-items-center gap-2">
                        <div>
                            <div class="payment-detail-label">Binance Pay ID</div>
                            <div class="payment-detail-val">496423006</div>
                        </div>
                        <span class="copy-badge ms-auto" onclick="copyText('496423006','bn')">
                            <i class="bi bi-copy me-1"></i><span id="bn">Copy</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     PAYMENT SUBMISSION FORM
══════════════════════════════════════════════════════════ --}}
<div class="row justify-content-center mb-5" id="paymentFormSection">
    <div class="col-lg-7">
        <div class="card border-0 shadow" style="border-radius:20px;overflow:hidden">
            <div class="card-header text-white py-3 px-4" style="background:linear-gradient(90deg,#1e40af,#1d4ed8)">
                <h5 class="fw-bold mb-0"><i class="bi bi-send-check me-2"></i>Submit Payment Proof</h5>
                <small class="opacity-75">After paying, fill this form and upload your screenshot</small>
            </div>
            <div class="card-body p-4">

                @if(session('payment_success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    Payment submitted successfully! Our team will verify and activate your plan within 2–12 hours. You will receive a confirmation email once approved.
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
                @endif

                {{-- Selected Plan Banner (shown after clicking Get Plan button) --}}
                <div id="selectedPlanBanner" class="alert alert-primary d-flex align-items-center gap-2 mb-3" style="display:none!important;border-radius:12px">
                    <i class="bi bi-check2-circle fs-5"></i>
                    <div>
                        <strong>Selected Plan: </strong>
                        <span id="selectedPlanName" class="fw-bold"></span>
                        <span class="ms-2 text-muted small" id="selectedPlanPrice"></span>
                    </div>
                </div>

                <form method="POST"
                      action="{{ route('payment.submit', 1) }}"
                      enctype="multipart/form-data"
                      id="paymentForm">
                    @csrf

                    <div class="row g-3">
                        {{-- Plan Selection --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Select Plan <span class="text-danger">*</span></label>
                            <select name="plan_id" id="planSelect" class="form-select" required>
                                <option value="">— Choose Plan —</option>
                                @foreach(\App\Models\SubscriptionPlan::where('is_active',true)->orderBy('price')->get() as $plan)
                                <option value="{{ $plan->id }}"
                                        data-price="{{ $plan->price }}"
                                        data-name="{{ $plan->name }}"
                                        {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }} — PKR {{ number_format($plan->price) }}/mo
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Payment Method --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Payment Method <span class="text-danger">*</span></label>
                            <select name="method" id="methodSelect" class="form-select" required onchange="updatePaymentHint(this.value)">
                                <option value="">— Choose Method —</option>
                                <option value="jazzcash"  {{ old('method')=='jazzcash'?'selected':'' }}>JazzCash</option>
                                <option value="easypaisa" {{ old('method')=='easypaisa'?'selected':'' }}>EasyPaisa</option>
                                <option value="binance"   {{ old('method')=='binance'?'selected':'' }}>Binance Pay</option>
                            </select>
                            <div id="methodHint" class="form-text mt-1"></div>
                        </div>

                        {{-- Amount Preview --}}
                        <div class="col-12">
                            <div class="alert alert-primary py-2 px-3 d-flex justify-content-between align-items-center" id="amountPreview" style="display:none!important;border-radius:10px">
                                <span><i class="bi bi-info-circle me-1"></i>Amount to send:</span>
                                <strong id="amountDisplay" class="fs-5">—</strong>
                            </div>
                        </div>

                        {{-- Transaction ID --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Transaction ID / Reference Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                <input type="text"
                                       name="transaction_id"
                                       class="form-control"
                                       value="{{ old('transaction_id') }}"
                                       placeholder="e.g. TXN-20241231-00012345"
                                       required>
                            </div>
                            <div class="form-text">Copy the transaction ID from your payment receipt.</div>
                        </div>

                        {{-- Screenshot Upload --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Payment Screenshot <span class="text-danger">*</span></label>
                            <div class="upload-zone" id="uploadZone" onclick="document.getElementById('screenshotInput').click()">
                                <div id="uploadPlaceholder">
                                    <i class="bi bi-cloud-arrow-up mb-2 d-block"></i>
                                    <div class="fw-semibold mb-1">Click to upload screenshot</div>
                                    <div class="text-muted small">PNG, JPG or JPEG — Max 5MB</div>
                                </div>
                                <div id="uploadPreview" class="upload-preview d-none">
                                    <img id="previewImg" src="" alt="Preview">
                                    <div class="mt-2 small text-success fw-semibold" id="previewFileName"></div>
                                </div>
                            </div>
                            <input type="file"
                                   name="screenshot"
                                   id="screenshotInput"
                                   accept="image/*"
                                   class="d-none"
                                   required
                                   onchange="previewUpload(this)">
                        </div>

                        {{-- Notes --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Additional Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="2"
                                      placeholder="Any special instructions or notes for the admin...">{{ old('notes') }}</textarea>
                        </div>

                        {{-- Submit Button --}}
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold" style="border-radius:12px">
                                <i class="bi bi-send-check me-2"></i>Submit Payment Proof
                            </button>
                            <p class="text-center text-muted small mt-2 mb-0">
                                <i class="bi bi-shield-check me-1 text-success"></i>
                                Your payment will be verified and you will receive a confirmation email once approved.
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     FAQ
══════════════════════════════════════════════════════════ --}}
<div class="row justify-content-center mb-4">
    <div class="col-lg-8">
        <h5 class="fw-bold text-center mb-3">Frequently Asked Questions</h5>
        <div class="accordion" id="faqAcc">
           @foreach($faqs as $i => $faq)
    <div class="accordion-item faq-item border">
        <h2 class="accordion-header">
            <button class="accordion-button {{ $i>0?'collapsed':'' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq-{{ $faq->id }}">
                {{ $faq->question }}
            </button>
        </h2>
        <div id="faq-{{ $faq->id }}" class="accordion-collapse collapse {{ $i===0?'show':'' }}" data-bs-parent="#faqAcc">
            <div class="accordion-body text-muted" style="font-size:.9rem">{{ $faq->answer }}</div>
        </div>
    </div>
@endforeach
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     CONTACT CTA
══════════════════════════════════════════════════════════ --}}
<div class="text-center py-4 border-top mt-2">
    <p class="text-muted mb-2">Need help choosing a plan? Talk to us!</p>
    <a href="https://wa.me/923148379859?text=Hi, I want to know about SWF Portal subscription plans"
       target="_blank"
       class="btn btn-success fw-semibold px-4 me-2">
        <i class="bi bi-whatsapp me-2"></i>Chat on WhatsApp
    </a>
    <a href="mailto:swfhelpers@gmail.com" class="btn btn-outline-secondary px-4">
        <i class="bi bi-envelope me-2"></i>Email Us
    </a>
</div>

@endsection

@push('scripts')
<script>
"use strict";
/* ─── BILLING TOGGLE ──────────────────────────────────────── */
const billingPrices = {
    basic:  { monthly: 2000,  yearly: 12000 },
    pro:    { monthly: 3500,  yearly: 20000 },
    max:    { monthly: 7000,  yearly: 40000 },
};

function selectBilling(plan, period) {
    const monthly = document.getElementById(plan + 'Monthly');
    const yearly  = document.getElementById(plan + 'Yearly');
    if (!monthly || !yearly) return;
    monthly.classList.toggle('active', period === 'monthly');
    yearly.classList.toggle('active',  period === 'yearly');
    const price = billingPrices[plan]?.[period] ?? 0;
    const btn   = document.querySelector(`[onclick*="openPaymentForm('${plan.charAt(0).toUpperCase()+plan.slice(1)}"]`);
    if (btn) {
        const planName = plan.charAt(0).toUpperCase() + plan.slice(1);
        btn.setAttribute('onclick', `openPaymentForm('${planName}', ${price}, '${period}')`);
    }
}

/* ─── OPEN PAYMENT FORM — FIXED AUTO-SELECT ───────────────── */
function openPaymentForm(planName, price, period) {
    // Scroll to form
    document.getElementById('paymentFormSection').scrollIntoView({ behavior: 'smooth', block: 'start' });

    // Auto-select the plan in the dropdown
    const sel = document.getElementById('planSelect');
    if (sel) {
        let found = false;
        for (let i = 0; i < sel.options.length; i++) {
            const opt = sel.options[i];
            // Match by data-name attribute or text starts with planName
            if (opt.dataset.name === planName || opt.text.startsWith(planName)) {
                sel.selectedIndex = i;
                found = true;
                break;
            }
        }
        // Trigger change event to update amount preview
        sel.dispatchEvent(new Event('change'));
    }

    // Show selected plan banner
    const banner = document.getElementById('selectedPlanBanner');
    const nameEl = document.getElementById('selectedPlanName');
    const priceEl = document.getElementById('selectedPlanPrice');
    if (banner && nameEl && priceEl) {
        nameEl.textContent  = planName + ' Plan';
        priceEl.textContent = 'PKR ' + price.toLocaleString() + ' / ' + period;
        banner.style.removeProperty('display');
        banner.style.display = 'flex';
    }
}

/* ─── AMOUNT PREVIEW ──────────────────────────────────────── */
document.getElementById('planSelect')?.addEventListener('change', function() {
    const opt   = this.options[this.selectedIndex];
    const price = opt?.dataset?.price;
    const name  = opt?.dataset?.name;
    updateAmountPreview(price);

    // Also update banner if visible
    const banner = document.getElementById('selectedPlanBanner');
    if (banner && banner.style.display !== 'none' && name) {
        document.getElementById('selectedPlanName').textContent = name + ' Plan';
        document.getElementById('selectedPlanPrice').textContent = 'PKR ' + parseFloat(price).toLocaleString() + '/mo';
    }
});

function updateAmountPreview(price) {
    const el   = document.getElementById('amountPreview');
    const disp = document.getElementById('amountDisplay');
    if (!el || !disp) return;
    if (price && parseFloat(price) > 0) {
        el.style.removeProperty('display');
        el.style.display = 'flex';
        disp.textContent = 'PKR ' + parseFloat(price).toLocaleString();
    } else {
        el.style.display = 'none';
    }
}

/* ─── METHOD HINT ─────────────────────────────────────────── */
const hints = {
    jazzcash:  '📱 Send to: <strong>03227653486</strong> (Nisar Ahmed)',
    easypaisa: '📱 Send to: <strong>03227653486</strong> (Nisar Ahmed)',
    binance:   '🟡 Binance Pay ID: <strong>496423006</strong> (Katyar Traders 09)',
};
function updatePaymentHint(val) {
    document.getElementById('methodHint').innerHTML = hints[val] || '';
}

/* ─── SCREENSHOT PREVIEW ──────────────────────────────────── */
function previewUpload(input) {
    if (!input.files?.length) return;
    const file = input.files[0];
    if (file.size > 5 * 1024 * 1024) { alert('File too large. Max 5MB.'); input.value=''; return; }
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('uploadPlaceholder').classList.add('d-none');
        document.getElementById('uploadPreview').classList.remove('d-none');
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('previewFileName').textContent = file.name;
    };
    reader.readAsDataURL(file);
}

/* ─── DRAG & DROP ─────────────────────────────────────────── */
const zone = document.getElementById('uploadZone');
if (zone) {
    zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('dragging'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('dragging'));
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.classList.remove('dragging');
        const input = document.getElementById('screenshotInput');
        if (e.dataTransfer.files.length) { input.files = e.dataTransfer.files; previewUpload(input); }
    });
}

/* ─── COPY TO CLIPBOARD ───────────────────────────────────── */
function copyText(text, spanId) {
    navigator.clipboard.writeText(text).then(() => {
        const el = document.getElementById(spanId);
        if (el) { el.textContent = 'Copied!'; setTimeout(() => el.textContent = 'Copy', 2000); }
    });
}

/* ─── ON PAGE LOAD: Restore old selection if validation failed ── */
document.addEventListener('DOMContentLoaded', () => {
    const sel = document.getElementById('planSelect');
    if (sel && sel.value) {
        const opt = sel.options[sel.selectedIndex];
        updateAmountPreview(opt?.dataset?.price);
    }
});
</script>
@endpush