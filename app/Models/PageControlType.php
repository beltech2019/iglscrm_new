<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class PageControlType extends Model
{
    use  Notifiable;
    protected $primaryKey = 'control_type_id ';

    protected $table = "page_control_type";

    protected $fillable = [
        'update_counter',
        'control_name',
        'control_type',
        'control_description'
       
       
    ];

     use SoftDeletes;
	 protected $dates = ['deleted_at'];
}
