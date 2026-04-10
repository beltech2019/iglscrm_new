<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAssignRule extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $table = "tb_user_assign_rule";

    protected $fillable = [
        'user_id',
        'name',
        'Keyword',
        'social_type',
        'assign_type',
        'from_date',
        'to_date',
        'enable'
    ];
    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
