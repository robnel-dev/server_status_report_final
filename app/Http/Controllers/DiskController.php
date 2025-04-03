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
        // Validate input parameters
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'search' => 'nullable|string|max:255'
        ]);

        // Get input values with defaults
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : now()->startOfDay();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : now()->endOfDay();
        $search = strtolower($request->input('search', ''));

        

        // Fetch filtered data
        $disks = Storage::whereBetween('datecrt', [$startDate, $endDate])
            ->whereNotIn('svrip', ['192.168.1.110', '192.168.44.139'])
            ->where('svrstat', 1)
            ->orderBy('svrip')
            ->orderBy('drvletter')
            ->get()
            ->filter(function ($disk) use ($search) {
                return empty($search) || collect([
                    $disk->svrip, 
                    $disk->server_name, 
                    $disk->drvletter, 
                    $disk->uom, 
                    $disk->drvsizetotal, 
                    $disk->drvsize_free, 
                    $disk->datecrt, 
                    $disk->status
                ])->map(fn ($value) => strtolower($value))->contains(fn ($val) => str_contains($val, $search));
            });

        // Transform Data for Frontend
        return Inertia::render('Disks', [
            'disks' => $disks->map(fn ($disk) => [
                'date' => Carbon::parse($disk->datecrt)->format('Y-m-d'),
                'server_ip' => $disk->svrip,
                'server_name' => $disk->server_name,
                'drive' => $disk->drvletter,
                'total_size' => $disk->drvsizetotal,
                'free_space' => $disk->drvsize_free,
                'uom' => $disk->uom,
                'status' => $disk->status
            ])
        ]);
    }
}
