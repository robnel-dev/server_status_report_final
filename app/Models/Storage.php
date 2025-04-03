<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Storage extends Model
{
    use HasFactory;

    protected $table = 'svrstorage_db';
    protected $fillable = [
        'svrip', 
        'server_name', 
        'drvletter', 
        'drvsizetotal',
        'drvsize_free', 
        'uom',
        'datecrt', 
        'timecrt',
        'svrstat'
    ];

    protected $casts = [
        'datecrt' => 'datetime',
    ];

    // Status calculation with error prevention
    public function getStatusAttribute()
    {
        $total = $this->uom === 'MB' ? $this->drvsizetotal / 1024 : $this->drvsizetotal;
        $free = $this->uom === 'MB' ? $this->drvsize_free / 1024 : $this->drvsize_free;

        // Prevent division by zero
        $percentage = $total > 0 ? ($free / $total) * 100 : 0;

        return match(true) {
            $percentage <= 20 => 'Critical 🔴',
            $percentage <= 25 => 'Warning 🟡',
            default => 'Normal 🟢'
        };
    }

    // Scopes for query organization
    public function scopeActive($query)
    {
        return $query->where('svrstat', 1);
    }

    public function scopeExcludeIps($query, array $ips)
    {
        return $query->whereNotIn('svrip', $ips);
    }

    public function scopeDateRange($query, $start, $end)
    {
        return $query->whereBetween('datecrt', [$start, $end]);
    }
}