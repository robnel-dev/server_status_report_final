<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Antivirus extends Model
{
    use HasFactory;
    protected $connection = 'mysql_company';
    protected $table = 'svrantivirus_db';
    protected $primaryKey = 'cntr';
    public $timestamps = false; 

    protected $fillable = [
        'svrIP',
        'last_update',
        'last_scan',
        'dateCRT',
        'timeCRT',
        'svrStat',
        'remarks'
    ];

    protected $casts = [
        'svrStat' => 'integer',
        'dateCRT' => 'string', 
    ];

    public function getFormattedDateAttribute()
    {
        return Carbon::createFromFormat('Ymd', $this->dateCRT)->format('Y-m-d');
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
