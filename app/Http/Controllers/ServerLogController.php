<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServerLogs;
use Inertia\Inertia;
use Carbon\Carbon;

class ServerLogController extends Controller
{
    public function index(Request $request)
    {
        // Validate request inputs
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        // Format start and end dates to match database format (YYYYMMDD)
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->format('Ymd')
            : now()->format('Ymd');

        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->format('Ymd')
            : now()->format('Ymd');


        // Fetch logs using model query scopes
        $logs = ServerLogs::active()
            ->dateRange($startDate, $endDate)
            ->orderBy('svrIP')
            ->orderBy('fileName')
            ->get();


        return Inertia::render('Logs', [
            'logs' => $logs->map(function ($log) {
                return [
                    'id' => $log->cntr,
                    'server_ip' => $log->server_ip, 
                    'filename' => $log->fileName,
                    'filesize' => $log->fileSize,
                    'datecrt' => $log->formatted_date, 
                    'timecrt' => $log->timeCRT,
                    'remarks' => $log->remarks ?? 'N/A',
                    'date_modified' => $log->dateMod ?? 'N/A',
                    'backup' => $log->bckupIn ? 'Yes' : 'No'
                ];
            })
        ]);
    }
}
