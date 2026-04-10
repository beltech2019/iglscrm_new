<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class GetTweet extends Model
{
    use  Notifiable;
    protected $primaryKey = 'id';

    protected $table = "tb_gettweet";

    protected $fillable = [
        'getTweet_id',
        'postMessage',
        'socialUser_name',
        'socialUser_userName',
        'socialUser_id',
        'source',
        'postUrl',
        'istPostDate',
        'postDate',
        'mobile_no',
        'email_id',
        'assignedto',
        'status',
		'converted',
        'convertLead',
        'post_category',
        'post_reply',
        'other_info',
        'responed',
        'responseDate',
        'type',
        'dm_status',
        'dm_startdate',
        'conversation_id',
        'bp_number',
        'department',
    ];
    use SoftDeletes;
	protected $dates = ['deleted_at'];

}
