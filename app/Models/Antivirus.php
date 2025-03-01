<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antivirus extends Model
{
    protected $table = 'svrantivirus_db';
    protected $fillable = ['svrip', 'last_update', 'datecrt', 'timecrt'];
}
