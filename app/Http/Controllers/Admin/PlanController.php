<?php
// FILE PATH: app/Http/Controllers/Admin/PlanController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        return view('admin.plans.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:100|unique:subscription_plans,name',
            'price' => 'required|numeric|min:0',
        ]);

        SubscriptionPlan::create([
            'name'      => $request->name,
            'price'     => $request->price,
            'is_active' => true,
        ]);

        return back()->with('success', 'Plan created successfully.');
    }

    public function update(Request $request, SubscriptionPlan $plan)
    {
        $request->validate([
            'name'  => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
        ]);

        $plan->update([
            'name'  => $request->name,
            'price' => $request->price,
        ]);

        return back()->with('success', 'Plan updated successfully.');
    }

    public function toggle(SubscriptionPlan $plan)
    {
        $plan->update(['is_active' => !$plan->is_active]);
        return back()->with('success', 'Plan status updated.');
    }
}