<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadTicket extends Model
{
    use  Notifiable;
    protected $primaryKey = 'id';

    protected $table = "tb_leads";

    protected $fillable = [
        'leadId',
        'getTweet_id',
        'greeting_first_name',
        'socialUser_id',
        'first_name',
        'last_name',
        'type',
        'title',
        'department',
        'customer_name',
        'status',
        'office_phone',
        'mobile',
        'website',
        'approval_status',
        'primary_address',
        'primary_city',
        'primary_state',
        'primary_postal_code',
        'primary_country',
        'other_address',
        'other_city',
        'other_state',
        'other_postal_code',
        'other_country',
        'email_address',
        'converted',
        'convertedtoticket',
        'description',
        'fax',
        'partner_contacts',
        'lead_source',
        'assigned_to',
        'bp_number',
        'resolution',
        'leadBy',
        'leadById',
        'created_date',
    ];
		 use SoftDeletes;
		 protected $dates = ['deleted_at'];
}


