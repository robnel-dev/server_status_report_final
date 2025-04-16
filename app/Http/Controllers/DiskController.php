<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Storage;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DiskController extends Controller
{
    public function index(Request $request)
    {

        // Validate request inputs
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'search' => 'nullable|string|max:255'
        ]);

   
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date'))->format('Ymd') 
            : now()->format('Ymd');

        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date'))->format('Ymd') 
            : now()->format('Ymd');
        
        $search = strtolower($request->input('search', ''));

        // Fetch filtered data
        $disks = Storage::active()
            //->excludeIPs(['192.168.1.110', '192.168.44.139']) //For Confirmation to sir Bryan
            ->dateRange($startDate, $endDate)
            ->orderBy('dateCRT', 'asc')
            ->orderBy('svrIP')
            ->orderBy('drvLetter')
            ->get()
            ->filter(function ($disk) use ($search) {
                return empty($search) || collect([
                    $disk->svrIP,
                    $disk->drvLetter,
                    (string) $disk->drvSizeTotal,
                    (string) $disk->drvSizeFree,
                    $disk->dateCRT,
                    $disk->status
                ])->filter()
                  ->map(fn($value) => strtolower((string) $value))
                  ->contains(fn($val) => str_contains($val, $search));
            });
            
            
            

        // Transform Data for Frontend
        return Inertia::render('Disks', [
            'disks' => $disks->map(fn($disk) => [
                'date' => $disk->formatted_date, 
                'server_ip' => $disk->svrIP,
                'drive' => $disk->drvLetter,
                'total_size' => $disk->drvSizeTotal,
                'free_space' => $disk->drvSizeFree,
                'status' => $disk->status
            ])
        ]);
    }
}
