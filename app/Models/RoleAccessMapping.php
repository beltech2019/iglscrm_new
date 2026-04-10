<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class RoleAccessMapping extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $table = "tb_role_access_mapping";

    protected $fillable = [
        'access',
        'user_role_id',
        'component_id'
    ];
    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
