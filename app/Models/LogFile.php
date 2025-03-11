<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogFile extends Model
{
    use HasFactory;
    protected $table = 'svrfiles_db';
    protected $fillable = ['svrip', 'filename', 'filesize', 'datecrt', 'timecrt'];

    // Add date casting for proper filtering
    protected $casts = [
        'datecrt' => 'date'
    ];

    // IP replacement accessor
    public function getServerIpAttribute()
    {
        return $this->svrip === '192.168.1.239' 
            ? '192.168.1.20' 
            : $this->svrip;
    }
}