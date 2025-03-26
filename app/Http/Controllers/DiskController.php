<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Storage;
use Inertia\Inertia;
use Carbon\Carbon;

class DiskController extends Controller
{
    public function index(Request $request)
    {
        // Validate request inputs
        $validated = $request->validate([
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
            'search' => 'nullable|string|max:255'
        ]);

        // Set start and end dates
        $startDate = isset($validated['start_date'])
            ? Carbon::parse($validated['start_date'])->startOfDay()
            : now()->startOfDay();

        $endDate = isset($validated['end_date'])
            ? Carbon::parse($validated['end_date'])->endOfDay()
            : now()->endOfDay();

        $search = $validated['search'] ?? null;

        // Fetch filtered data from the database
        $disks = Storage::dateRange($startDate, $endDate)
            ->excludeIps(['192.168.1.110', '192.168.44.139'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('svrip', 'LIKE', "%{$search}%")
                        ->orWhere('server_name', 'LIKE', "%{$search}%")
                        ->orWhere('drvletter', 'LIKE', "%{$search}%")
                        ->orWhere('uom', 'LIKE', "%{$search}%")
                        ->orWhere('drvsizetotal', 'LIKE', "%{$search}%")
                        ->orWhere('drvsize_free', 'LIKE', "%{$search}%")
                        ->orWhere('datecrt', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "%{$search}%"); // Now searchable in SQL
                });
            })
            ->orderBy('svrip')
            ->orderBy('drvletter')
            ->get();

        // Return data to Inertia with formatted attributes
        return Inertia::render('Disks', [
            'disks' => $disks->map(fn($disk) => [
                'date' => $disk->datecrt_formatted,
                'server_ip' => $disk->svrip,
                'server_name' => $disk->server_name,
                'drive' => $disk->drvletter,
                'total_size' => "{$disk->drvsizetotal} {$disk->uom}",
                'free_space' => "{$disk->drvsize_free} {$disk->uom}",
                'status' => $disk->status, // Ensure status is included
            ])
        ]);
        
    }
}
