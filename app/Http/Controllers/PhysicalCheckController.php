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
        // Get dates from request or default to today
        $startDate = $request->input('start_date', now()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $checks = PhysicalCheck::whereBetween('created_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay()
        ])
        ->latest()
        ->get();

        return Inertia::render('PhysicalChecks', [
            'checks' => $checks->map(function ($check) {
                return [
                    'id' => $check->id,
                    'in_charge' => $check->in_charge,
                    'aircon_status' => $check->aircon_status,
                    'amber_alert' => $check->amber_alert,
                    'remarks' => $check->remarks,
                    'created_at' => $check->created_at->toDateTimeString()
                ];
            })
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'in_charge' => 'required|string',
            'aircon_status' => 'required|in:Normal,Faulty',
            'amber_alert' => 'required|boolean',
            'remarks' => 'nullable|string'
        ]);

        PhysicalCheck::create($request->all());
        return redirect()->back();
    }


    public function update(Request $request, PhysicalCheck $check)
    {
        $request->validate([
            'in_charge' => 'required|string',
            'aircon_status' => 'required|in:Normal,Faulty',
            'amber_alert' => 'required|boolean',
            'remarks' => 'nullable|string'
        ]);

        $check->update($request->all());
        return redirect()->back();
    }

    public function destroy(PhysicalCheck $check)
    {
        
        $check->update(['remarks' => '']);
        return redirect()->back();
    }
}
