<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
class SocialTicket extends Model
{
    use  Notifiable;
    protected $primaryKey = 'id';

    protected $table = "tb_socialticket";

    protected $fillable = [
        'ticket_id',
        'getTweet_id',
        'postMessage',
        'socialUser',
        'source',
        'postUrl',
        'postDate',
        'mobile_no',
        'email_id',
        'assigned_to',
        'subSource',
        'socialUser_id',
        'status',
        'type',
        'date_Created',
        'priority',
        'bipNumber',
        'subject',
        'suggestion',
        'description',
        'additional_Text',
        'resolution',
        'final_state',
        'converted',
        'internalUpdate',
       
    ];
		 use SoftDeletes;
	 protected $dates = ['deleted_at'];
}


