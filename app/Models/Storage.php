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
        'svrip', 'server_name', 'drvletter', 'drvsizetotal',
        'drvsize_free', 'uom', 'datecrt', 'timecrt', 'status'
    ];

    /**
     * Cast attributes to ensure proper data types.
     */
    protected $casts = [
        'datecrt' => 'datetime', // Ensures datecrt is treated as a Carbon instance
    ];

    /**
     * Scope: Exclude certain IPs.
     */
    public function scopeExcludeIps($query, array $ips)
    {
        return $query->whereNotIn('svrip', $ips);
    }

    /**
     * Scope: Filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('datecrt', [$startDate, $endDate]);
    }

    /**
     * Accessor: Format datecrt as 'Y-m-d' when accessed.
     */
    public function getDatecrtFormattedAttribute()
    {
        return $this->datecrt ? Carbon::parse($this->datecrt)->format('Y-m-d') : null;
    }

    /**
     * Automatically set status before saving to the database.
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function ($storage) {
            $storage->status = $storage->calculateStatus();
        });
    }

    /**
     * Calculate free space percentage and return status.
     */
    public function calculateStatus()
    {
        $totalSizeGB = $this->uom === 'MB' ? $this->drvsizetotal / 1024 : $this->drvsizetotal;
        $freeSpaceGB = $this->uom === 'MB' ? $this->drvsize_free / 1024 : $this->drvsize_free;

        // Avoid division by zero
        $percentage = $totalSizeGB > 0 ? ($freeSpaceGB / $totalSizeGB) * 100 : 0;

        return match (true) {
            $percentage <= 20 => 'Critical 🔴',
            $percentage <= 25 => 'Warning 🟡',
            default => 'Normal 🟢'
        };
    }
}
