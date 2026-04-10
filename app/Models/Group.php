<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class Group extends Model
{
    use  Notifiable;
    protected $primaryKey = 'id';

    protected $table = "tb_group";

    protected $fillable = [
        'group_name',
        'last_message_time',
        'status'
    ];

     use SoftDeletes;
	 protected $dates = ['deleted_at'];
}
