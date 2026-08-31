<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesforceCase extends Model
{
    protected $table = 'salesforce_cases';

    protected $fillable = [
        'ticket_id',
        'salesforce_case_id',
        'case_number',
        'record_type',
        'status',
    ];
}