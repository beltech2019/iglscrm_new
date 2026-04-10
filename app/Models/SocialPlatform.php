<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class SocialPlatform extends Model
{
    use  Notifiable;
    protected $primaryKey = 'id';

    protected $table = "tb_socialplatform";

    protected $fillable = [
        'key',
        'value',
        'label',
        'status',
    ];

     use SoftDeletes;
	 protected $dates = ['deleted_at'];
}
