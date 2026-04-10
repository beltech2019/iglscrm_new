<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class Option extends Model
{
    use  Notifiable;
    protected $primaryKey = 'id';

    protected $table = "tb_option";

    protected $fillable = [
        'key',
        'label',
        'sortOrder',
        'value',
        'subvalue',
        'specialLogic',
        'status'
    ];

     use SoftDeletes;
	 protected $dates = ['deleted_at'];
}
