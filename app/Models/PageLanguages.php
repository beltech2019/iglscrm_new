<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class PageLanguages extends Model
{
    use  Notifiable;
    protected $primaryKey = 'language_id ';

    protected $table = "page_languages";

    protected $fillable = [
        'update_counter',
        'name',
        'description',
        'language_code'
       
       
    ];

     use SoftDeletes;
	 protected $dates = ['deleted_at'];
}
