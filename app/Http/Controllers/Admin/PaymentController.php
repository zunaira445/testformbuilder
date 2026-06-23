<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Payment, UserSubscription};
use Illuminate\Http\Request;

class PaymentController extends Controller {
    public function index() {
        $payments = Payment::with(['user','plan'])->latest()->paginate(20);
        return view('admin.payments.index', compact('payments'));
    }

    public function approve(Request $request, Payment $payment) {
        $payment->update([
            'status'      => 'approved',
            'admin_note'  => $request->admin_note,
            'approved_at' => now(),
        ]);
        // Subscription activate karo (30 din)
        UserSubscription::where('user_id', $payment->user_id)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        UserSubscription::create([
            'user_id'              => $payment->user_id,
            'subscription_plan_id' => $payment->subscription_plan_id,
            'payment_id'           => $payment->id,
            'expires_at'           => now()->addDays(30),
            'is_active'            => true,
        ]);
        return back()->with('success', 'Payment approve aur subscription activate ho gaya.');
    }

    public function reject(Request $request, Payment $payment) {
        $payment->update([
            'status'     => 'rejected',
            'admin_note' => $request->admin_note,
        ]);
        return back()->with('success', 'Payment reject kar di.');
    }
}