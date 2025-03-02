<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Storage;
use Inertia\Inertia;

class DiskController extends Controller
{
    public function index() {
        $disks = Storage::whereDate('datecrt', today())
            ->whereNotIn('svrip', ['192.168.1.110', '192.168.44.139'])
            ->where('svrstat', 1)
            ->orderBy('svrip')
            ->get();

            return Inertia::render('Disks', [
                'disks' => $disks->map(function ($disk) {
                    return [
                        'server_ip' => $disk->svrip,
                        'server_name' => $disk->server_name,
                        'drive' => $disk->drvletter,
                        'total_size' => $disk->drvsizetotal,
                        'free_space' => $disk->drvsize_free,
                        'uom' => $disk->uom, // Pass unit to the view
                        'status' => $disk->status
                    ];
                })
            ]);
    }
}
