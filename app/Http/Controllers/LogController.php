<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogFile;
use Inertia\Inertia;

class LogController extends Controller
{
    public function index(Request $request)
    {
        // Get dates from request or default to today
        $startDate = $request->input('start_date', now()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $logs = LogFile::whereBetween('datecrt', [$startDate, $endDate])
            ->where('filename', '<>', 'DB.XLSX')
            ->where('svrstat', 1)
            ->orderBy('svrip')
            ->get();

        return Inertia::render('Logs', [
            'logs' => $logs->map(function ($log) {
                return [
                    'id' => $log->id,
                    'server_ip' => $log->server_ip, // Using the accessor
                    'filename' => $log->filename,
                    'filesize' => $log->filesize,
                    'datecrt' => $log->datecrt,
                    'timecrt' => $log->timecrt
                ];
            })
        ]);
    }
}