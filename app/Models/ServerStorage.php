<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class ServerStorage extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql_company'; 
    protected $table = 'svrstorage_db';
    protected $primaryKey = 'cntr';
    public $incrementing = true;
    public $timestamps = false; 

    protected $fillable = [
        'svrIP', 
        'drvLetter', 
        'drvSizeTotal', 
        'drvSizeFree', 
        'dateCRT', 
        'timeCRT', 
        'svrStat', 
        'remarks'
    ];

    // Ensure correct data types
    protected $casts = [
        'svrStat' => 'integer',
        'dateCRT' => 'string', 
    ];

    /**
     * Convert stored dateCRT (YYYYMMDD) to Y-m-d
     */
    public function getFormattedDateAttribute()
    {
        return Carbon::createFromFormat('Ymd', $this->dateCRT)->format('Y-m-d');
    }

    /**
     * Convert sizes from MB/GB to a numeric GB value
     */
    private function parseSize($size)
    {
        preg_match('/([\d.]+)\s*(MB|GB|TB)?/i', $size, $matches);
        if (!isset($matches[1])) return 0; // Default to 0 if no match
        $value = (float) $matches[1];
        $unit = strtoupper($matches[2] ?? 'GB'); // Default to GB

        return match ($unit) {
            'MB' => round($value / 1024, 2), // Convert MB to GB
            'TB' => $value * 1024, // Convert TB to GB
            default => $value // Assume GB
        };
    }

    /**
     * Get storage status
     */
    public function getStatusAttribute()
    {
        $total = $this->parseSize($this->drvSizeTotal);
        $free = $this->parseSize($this->drvSizeFree);
        $percentage = ($total > 0) ? ($free / $total) * 100 : 0;

        return match(true) {
            $percentage <= 20 => 'Critical 🔴',
            $percentage <= 25 => 'Warning 🟡',
            default => 'Normal 🟢'
        };
    }

    /**
     * Scope to filter active servers
     */
    public function scopeActive($query)
    {
        return $query->where('svrStat', 1);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $start, $end)
{
    return $query->whereRaw("CAST(dateCRT AS CHAR) BETWEEN ? AND ?", [(string) $start, (string) $end]);
}

}
