<?php
// FILE PATH: app/Http/Controllers/Admin/PaymentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PaymentApprovedMail;
use App\Models\{Payment, UserSubscription};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            return back()->with('error', 'This payment has already been approved.');
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

        // Payment fresh reload karo with relationships
        $payment->load(['user', 'plan']);

        // Email IMMEDIATELY bhejo — no queue, synchronous
        try {
            Mail::to($payment->user->email)
                ->send(new PaymentApprovedMail($payment));
        } catch (\Exception $e) {
            // Email fail hone par bhi approve ho jaye — sirf log karo
            Log::error('Payment approval email failed for user ' . $payment->user->email . ': ' . $e->getMessage());
        }

        return back()->with('success', '✅ Payment approved and subscription activated. A confirmation email has been sent to the user!');
    }

    // ── Reject Payment ────────────────────────────────────────
    public function reject(Request $request, Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Only pending payments can be rejected.');
        }

        $payment->update([
            'status'     => 'rejected',
            'admin_note' => $request->admin_note,
        ]);

        return back()->with('success', 'Payment has been rejected.');
    }
}