<?php
// FILE PATH: app/Http/Controllers/Admin/PaymentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PaymentApprovedMail;
use App\Models\{Payment, UserSubscription};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    // ── List All Payments ─────────────────────────────────────
    public function index()
    {
        $payments = Payment::with(['user', 'plan'])->latest()->paginate(20);
        return view('admin.payments.index', compact('payments'));
    }

    // ── Approve Payment ───────────────────────────────────────
    public function approve(Request $request, Payment $payment)
    {
        // Already approved check
        if ($payment->status === 'approved') {
            return back()->with('error', 'Yeh payment pehle hi approve ho chuki hai.');
        }

        // Payment approve karo
        $payment->update([
            'status'      => 'approved',
            'admin_note'  => $request->admin_note,
            'approved_at' => now(),
        ]);

        // Purani active subscription deactivate karo
        UserSubscription::where('user_id', $payment->user_id)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        // Naya subscription create karo (30 din)
        UserSubscription::create([
            'user_id'              => $payment->user_id,
            'subscription_plan_id' => $payment->subscription_plan_id,
            'payment_id'           => $payment->id,
            'expires_at'           => now()->addDays(30),
            'is_active'            => true,
        ]);

        // User ko email bhejo (fresh reload karo payment)
        $payment->load(['user', 'plan']);

        try {
            Mail::to($payment->user->email)
                ->send(new PaymentApprovedMail($payment));
        } catch (\Exception $e) {
            // Email fail hone par bhi approve ho jaye — sirf log karo
            \Log::error('Payment approval email failed: ' . $e->getMessage());
        }

        return back()->with('success', '✅ Payment approve aur subscription activate ho gaya. User ko email bhi bhej di gayi!');
    }

    // ── Reject Payment ────────────────────────────────────────
    public function reject(Request $request, Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Sirf pending payments reject ki ja sakti hain.');
        }

        $payment->update([
            'status'     => 'rejected',
            'admin_note' => $request->admin_note,
        ]);

        return back()->with('success', 'Payment reject kar di gayi.');
    }
}