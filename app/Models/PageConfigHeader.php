<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class PageConfigHeader extends Model
{
    use  Notifiable;
    protected $primaryKey = 'page_config_header_id ';

    protected $table = "page_config_header";

    protected $fillable = [
        'page_type',
        'update_counter',
        'total_questions',
        'catagory',
        'description'
       
    ];

     use SoftDeletes;
	 protected $dates = ['deleted_at'];
}
