<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Storage;
use Inertia\Inertia;

class DiskController extends Controller
{
    public function index(Request $request)
    {
        // Get dates from request or default to today
        $startDate = $request->input('start_date', now()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        // Query with date range
        $disks = Storage::whereBetween('datecrt', [$startDate, $endDate])
            ->whereNotIn('svrip', ['192.168.1.110', '192.168.44.139'])
            ->where('svrstat', 1)
            ->orderBy('svrip')
            ->orderBy('drvletter')
            ->get();

        return Inertia::render('Disks', [
            'disks' => $disks->map(function ($disk) {
                return [
                    'date' => $disk->datecrt,
                    'server_ip' => $disk->svrip,
                    'server_name' => $disk->server_name,
                    'drive' => $disk->drvletter,
                    'total_size' => $disk->drvsizetotal,
                    'free_space' => $disk->drvsize_free,
                    'uom' => $disk->uom,
                    'status' => $disk->status
                ];
            })
        ]);
    }
}