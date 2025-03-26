<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhysicalCheck;
use Inertia\Inertia;
use Carbon\Carbon;

class PhysicalCheckController extends Controller
{
    public function index(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date', now()))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date', now()))->endOfDay();

        $checks = PhysicalCheck::whereBetween('created_at', [$startDate, $endDate])->latest()->get();

        return Inertia::render('PhysicalChecks', [
            'checks' => $checks->map(fn($check) => [
                'id' => $check->id,
                'in_charge' => $check->in_charge,
                'aircon_status' => $check->aircon_status,
                'amber_alert' => $check->amber_alert,
                'remarks' => $check->remarks,
                'created_at' => $check->created_at->toDateTimeString()
            ])
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'in_charge' => 'required|string',
            'aircon_status' => 'required|in:Normal,Faulty',
            'amber_alert' => 'required|boolean',
            'remarks' => 'nullable|string'
        ]);

        PhysicalCheck::create($validated);
        return redirect()->back()->with('success', 'Record added successfully.');
    }

    public function update(Request $request, PhysicalCheck $check)
    {
        $validated = $request->validate([
            'in_charge' => 'required|string',
            'aircon_status' => 'required|in:Normal,Faulty',
            'amber_alert' => 'required|boolean',
            'remarks' => 'nullable|string'
        ]);

        $check->update($validated);
        return redirect()->back()->with('success', 'Record updated successfully.');
    }

    public function clearRemarks(PhysicalCheck $check)
    {
        $check->update(['remarks' => null]);
        return redirect()->back()->with('success', 'Remarks cleared.');
    }
}
