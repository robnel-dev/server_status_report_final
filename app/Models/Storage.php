<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Storage extends Model
{
    use HasFactory;
    protected $table = 'svrstorage_db';
    protected $fillable = ['svrip', 'server_name', 'drvletter', 'drvsizetotal', 'drvsize_free','oum', 'datecrt', 'timecrt'];
    
     // Calculate free space percentage (handle GB/MB)
     public function getStatusAttribute() {
        $totalSizeGB = $this->uom === 'MB' ? $this->drvsizetotal / 1024 : $this->drvsizetotal;
        $freeSpaceGB = $this->uom === 'MB' ? $this->drvsize_free / 1024 : $this->drvsize_free;

        $percentage = ($freeSpaceGB / $totalSizeGB) * 100;

        return match(true) {
            $percentage <= 20 => 'Critical 🔴',
            $percentage <= 25 => 'Warning 🟡',
            default => 'Normal 🟢'
        };
    }
}
