<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    protected $table = 'svrstorage_db';
    protected $fillable = ['svrip', 'server_name', 'drvletter', 'drvsizetotal', 'drvsize_free', 'datecrt', 'timecrt'];
    
    // Calculate free space percentage
    public function getFreeSpaceAttribute() {
        return (int) preg_replace('/[^0-9]/', '', $this->drvsize_free);
    }
}
