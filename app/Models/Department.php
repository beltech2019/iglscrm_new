<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory;
    protected $primaryKey = 'department_id';

    protected $table = "tb_department";

    protected $fillable = [
        'department_name',
        'department_code',
       

        
    ];
    use SoftDeletes;
	protected $dates = ['deleted_at'];
}

