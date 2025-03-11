<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PhysicalCheck extends Model
{
    use HasFactory;
    
    protected $fillable = ['in_charge', 'aircon_status', 'amber_alert', 'remarks'];
    
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'amber_alert' => 'boolean'
    ];
}