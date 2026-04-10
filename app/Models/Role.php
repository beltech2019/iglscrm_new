<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;
    protected $primaryKey = 'role_id';

    protected $table = "tb_roles";

    protected $fillable = [
        'role_name',
        'role_key',
        'role_order',
	];
    use SoftDeletes;
	protected $dates = ['deleted_at'];
}
