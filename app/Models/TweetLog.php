<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class TweetLog extends Model
{
    use HasFactory;
    protected $primaryKey = 'log_id';

    protected $table = "tb_change_log";

    protected $fillable = [
        'new_value',
        'old_value',
        'change_by',
        'change_date',
        'post_id',
        'post_type',
        'field',
        'assignto_by_id',
    ];

     use SoftDeletes;
	 protected $dates = ['deleted_at'];
}
