<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Conversation extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $table = "tb_conversation";

    protected $fillable = [
        'conversation_id',
        'socialpost_id',
        'message_id',
        'sender_id',
        'sender_name',
        'sender_username',
        'source',
        'message',
        'message_time'
    ];
    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
