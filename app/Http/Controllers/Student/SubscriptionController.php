<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        // 1. Plans fetch karein
        $plans = SubscriptionPlan::where('is_active', true)->orderBy('price')->get();
        
        // 2. Active subscription check (Agar login ho)
        $sub = Auth::check() ? Auth::user()->activeSubscription : null;

        // 3. FAQs ka array yahan define karein taake View mein error na aaye
        $faqs = [
            (object)['id' => 1, 'question' => 'How long does plan activation take?', 'answer' => 'After submitting your payment proof, our team verifies it within 2–12 hours.'],
            (object)['id' => 2, 'question' => 'Can I switch plans mid-cycle?', 'answer' => 'Yes! You can upgrade anytime. Your remaining days will be adjusted proportionally.'],
            (object)['id' => 3, 'question' => 'Is there a free trial?', 'answer' => 'Every new instructor gets a free starter tier allowing up to 5 tests and 50 students.'],
            (object)['id' => 4, 'question' => 'How is yearly billing handled?', 'answer' => 'Pay once for the full year at the discounted price and get 12 months of access.'],
            (object)['id' => 5, 'question' => 'What payment methods are accepted?', 'answer' => 'We accept JazzCash, EasyPaisa, and Binance Pay.'],
        ];

        // 4. Compact mein $faqs zaroor likhein
        return view('student.subscription', compact('plans', 'sub', 'faqs'));
    }

    public function submit(Request $request, $planId) {
    $request->validate([
        'plan_id'        => 'required|exists:subscription_plans,id',
        'method'         => 'required|in:jazzcash,easypaisa,binance',
        'transaction_id' => 'required|string',
        'screenshot'     => 'required|image|max:5120',
    ]);

    $path = $request->file('screenshot')->store('payment-screenshots', 'public');

    \App\Models\Payment::create([
        'user_id'              => Auth::id(),
        'subscription_plan_id' => $request->plan_id,
        'method'               => $request->method,
        'amount'               => \App\Models\SubscriptionPlan::find($request->plan_id)->price,
        'transaction_id'       => $request->transaction_id,
        'screenshot'           => $path,
        'notes'                => $request->notes,
        'status'               => 'pending',
    ]);

    return back()->with('payment_success', true);
}
}