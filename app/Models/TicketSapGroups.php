<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketSapGroups extends Model
{
    use  Notifiable;
    protected $primaryKey = 'id';

    protected $table = "sap_ticket_set";

    protected $fillable = [
        'sap_ticket_status',
        'sap_object_id',
        'sap_process_type',
        'sap_code_group_id',
        'ticket_id',
        'sap_status'
    ];

     use SoftDeletes;
	 protected $dates = ['deleted_at'];
}
