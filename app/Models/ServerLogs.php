<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
class ServerLogs extends Model
{
    use HasFactory;
    protected $connection = 'mysql_company'; 
    protected $table = 'svrfiles_db'; 
    protected $primaryKey = 'cntr'; 
    public $timestamps = false; 

    protected $fillable = [
        'svrIP', 'fileName', 'fileSize', 'dateCRT', 'timeCRT', 'svrStat', 'remarks', 'dateMod', 'bckupIn'
    ];

    // Cast dateCRT as date for proper filtering
    protected $casts = [
        'svrStat' => 'integer',
        'dateCRT' => 'string', 
    ];

    // IP accessor for replacement logic
    public function getServerIpAttribute()
    {
        return $this->svrIP === '192.168.1.239' 
            ? '192.168.1.20' 
            : $this->svrIP;
    }

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
