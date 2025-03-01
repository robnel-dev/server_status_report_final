<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Antivirus;
use Inertia\Inertia;

class AntivirusController extends Controller
{
    public function index() {
        $antiviruses = Antivirus::whereDate('datecrt', today())
            ->where('svrstat', 1)
            ->orderBy('svrip')
            ->get();

        return Inertia::render('Antivirus', [
            'antiviruses' => $antiviruses
        ]);
    }
}
