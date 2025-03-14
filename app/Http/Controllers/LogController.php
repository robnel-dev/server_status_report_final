<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogFile;
use Inertia\Inertia;

class LogController extends Controller
{
    public function index()
    {
        $logs = LogFile::whereDate('datecrt', today())
            ->where('filename', '<>', 'DB.XLSX')
            ->where('svrstat', 1)
            ->orderBy('svrip')
            ->get();

        return Inertia::render('Logs', [
            'logs' => $logs->map(function ($log) {
                return [
                    'server_ip' => $log->server_ip,
                    'filename' => $log->filename,
                    'filesize' => $log->filesize, // Ensure this key matches the template
                    'datecrt' => $log->datecrt, // Ensure this key matches the template
                    'timecrt' => $log->timecrt // Ensure this key matches the template

                ];
            })
        ]);
    }
}
