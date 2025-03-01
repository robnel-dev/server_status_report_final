<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhysicalCheck extends Model
{
    protected $fillable = ['in_charge', 'aircon_status', 'amber_alert', 'remarks'];
}
