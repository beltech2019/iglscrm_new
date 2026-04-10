<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $table = "tb_activity";

    protected $fillable = [
        'text',
        'email',
        'created_by',
        'type',
        'is_mail',
        'post_id',

        
    ];
    use SoftDeletes;
	protected $dates = ['deleted_at'];
}

