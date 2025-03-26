<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogFile extends Model
{
    use HasFactory;
    protected $table = 'svrfiles_db';
    protected $fillable = ['svrip', 'filename', 'filesize', 'datecrt', 'timecrt'];

    protected $casts = ['datecrt' => 'date'];

    // Scope: Exclude unwanted files
    public function scopeExcludeFiles($query, array $files)
    {
        return $query->whereNotIn('filename', $files);
    }

    // Accessor: Adjust server IP
    public function getServerIpAttribute()
    {
        return $this->svrip === '192.168.1.239' ? '192.168.1.20' : $this->svrip;
    }
}
