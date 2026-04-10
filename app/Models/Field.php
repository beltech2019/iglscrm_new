<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class Field extends Model
{
    use  Notifiable;
    protected $primaryKey = 'id';

    protected $table = "tb_fields";

    protected $fillable = [
        'report_id',
        'field_label',
        'field_key',
        'is_show',
    ];

     use SoftDeletes;
	 protected $dates = ['deleted_at'];
}
