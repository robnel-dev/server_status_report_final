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
        // Validate inputs
        $request->validate([
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
            'search' => 'nullable|string|max:255'
        ]);

        // Parse dates with Carbon
        $startDate = Carbon::parse($request->input('start_date', now()))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date', now()))->endOfDay();
        $search = $request->input('search');

        // Base query
        $disks = Storage::whereBetween('datecrt', [$startDate, $endDate])
            ->whereNotIn('svrip', ['192.168.1.110', '192.168.44.139'])
            ->where('svrstat', 1)
            ->orderBy('svrip')
            ->orderBy('drvletter')
            ->get();

        // Client-side filtering
        if ($search) {
            $disks = $disks->filter(function ($disk) use ($search) {
                $search = strtolower($search);
                return
                    str_contains(strtolower($disk->svrip), $search) ||
                    str_contains(strtolower($disk->server_name), $search) ||
                    str_contains(strtolower($disk->drvletter), $search) ||
                    str_contains(strtolower($disk->uom), $search) ||
                    str_contains(strtolower($disk->drvsizetotal), $search) ||
                    str_contains(strtolower($disk->drvsize_free), $search) ||
                    str_contains(strtolower($disk->datecrt), $search) ||
                    str_contains(strtolower($disk->status), $search);
            });
        }

        return Inertia::render('Disks', [
            'disks' => $disks->map(function ($disk) {
                return [
                    'date' => Carbon::parse($disk->datecrt)->format('Y-m-d'),
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
