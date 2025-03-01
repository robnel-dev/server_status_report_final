<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhysicalCheck;
use Inertia\Inertia;
class PhysicalCheckController extends Controller
{
    public function index() {
        $checks = PhysicalCheck::latest()->get();
        return Inertia::render('PhysicalChecks', ['checks' => $checks]);
    }

    public function store(Request $request) {
        $request->validate([
            'in_charge' => 'required|string',
            'aircon_status' => 'required|in:Normal,Faulty',
            'amber_alert' => 'required|boolean',
            'remarks' => 'nullable|string'
        ]);

        PhysicalCheck::create($request->all());
        return redirect()->back();
    }
}
