<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class PageQuestionMapping extends Model
{
    use  Notifiable;
    protected $primaryKey = 'page_question_mapping_id ';

    protected $table = "page_question_mapping";

    protected $fillable = [
        'page_config_id',
        'page_question_config_id',
        'update_counter',
        'is_mandatory?',
        'show_if_response(x_y)',
        'qtype',
        'special_logic_code',
        'response_arrangement',
        'no._of_columns'	
       
    ];

     use SoftDeletes;
	 protected $dates = ['deleted_at'];
}
