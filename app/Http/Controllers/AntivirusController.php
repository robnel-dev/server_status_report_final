<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Antivirus;
use Inertia\Inertia;

class AntivirusController extends Controller
{
    public function index(Request $request)
    {
        // Get dates from request or default to today
        $startDate = $request->input('start_date', now()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $antiviruses = Antivirus::whereBetween('datecrt', [$startDate, $endDate])
            ->where('svrstat', 1)
            ->orderBy('svrip')
            ->get();

        return Inertia::render('Antivirus', [
            'antiviruses' => $antiviruses->map(function ($av) {
                return [
                    'id' => $av->id,
                    'svrip' => $av->svrip,
                    'last_update' => $av->last_update,
                    'datecrt' => $av->datecrt,
                    'timecrt' => $av->timecrt
                ];
            })
        ]);
    }
}