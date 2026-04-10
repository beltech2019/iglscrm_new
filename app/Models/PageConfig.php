<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class PageConfig extends Model
{
    use  Notifiable;
    protected $primaryKey = 'page_config_id ';

    protected $table = "page_config";

    protected $fillable = [
        'update_counter',
        'page_name',
        'Page_desc',
        'Page_number',
        'icon_name'
       
    ];

     use SoftDeletes;
	 protected $dates = ['deleted_at'];
}
