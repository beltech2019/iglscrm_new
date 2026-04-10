<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class PageQuestionRespConfig extends Model
{
    use  Notifiable;
    protected $primaryKey = 'page_question_config_id ';

    protected $table = "page_question_resp_config";

    protected $fillable = [
        'update_counter',
        'responce_i18n_id'
        
       
    ];

     use SoftDeletes;
	 protected $dates = ['deleted_at'];
}
