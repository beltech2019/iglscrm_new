<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostAssignRule extends Model
{
    use HasFactory;
    protected $primaryKey = 'ruleid';

    protected $table = "tb_post_type_rules";

    protected $fillable = [
        'keyword',
        'category',
        'type',
        'status'
    ];
    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
