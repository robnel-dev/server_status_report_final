<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antivirus extends Model
{
    use HasFactory;
    protected $table = 'svrantivirus_db';
    protected $fillable = ['svrip', 'last_update', 'datecrt', 'timecrt'];
}
