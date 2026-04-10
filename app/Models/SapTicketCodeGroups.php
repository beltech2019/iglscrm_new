<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class SapTicketCodeGroups extends Model
{
    use  Notifiable;
    protected $primaryKey = 'id';

    protected $table = "code_group_set";

    protected $fillable = [
        'catalog_type',
        'code_group',
        'catlog_type_desc',
        'code',
        'group_text',
        'asp_id',
        'cat_id',
    ];

     use SoftDeletes;
	 protected $dates = ['deleted_at'];
}
