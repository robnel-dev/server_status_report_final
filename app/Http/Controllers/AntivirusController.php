<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Antivirus;
use Inertia\Inertia;
use Carbon\Carbon;
class AntivirusController extends Controller
{
    public function index(Request $request)
    {

        // Validate request inputs
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date'))->format('Ymd') 
            : now()->format('Ymd');

        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date'))->format('Ymd') 
            : now()->format('Ymd');

        $antiviruses = Antivirus::active()
            ->dateRange($startDate, $endDate)
            ->orderBy('svrIP')
            ->get();

        return Inertia::render('Antivirus', [
            'antiviruses' => $antiviruses->map(function ($av) {
                return [
                    'id' => $av->cntr,
                    'svrIP' => $av->svrIP,
                    'last_update' => $av->last_update,
                    'last_scan' => $av->last_scan,
                    'dateCRT' => $av->formatted_date,
                    'timeCRT' => $av->timeCRT,
                    'svrStat' => $av->svrStat,
                    'remarks' => $av->remarks,
                ];
            })
        ]);
    }
}
