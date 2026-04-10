<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class PageConfigDetails extends Model
{
    use  Notifiable;
    protected $primaryKey = 'page_config_details_id ';

    protected $table = "page_config_details";

    protected $fillable = [
        'page_config_header_id',
        'page_config_id',
        'update_counter',
        'answer_type',
        'range',
        'page_question_i18_id'
    ];

     use SoftDeletes;
	 protected $dates = ['deleted_at'];
}
