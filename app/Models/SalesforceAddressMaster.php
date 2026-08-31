<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesforceAddressMaster extends Model
{
    protected $table = 'salesforce_address_masters';

    protected $fillable = [
        'salesforce_id',
        'name',
        'area',
        'zone',
        'control_room_name',
        'city',
        'state',
        'pincode',
    ];
}