<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AdminChanges extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $table = "tb_admin_change";

    protected $fillable = [
        'new_value',
        'old_value',
        'change_by',
        'change_date',
        'table_name',
        'field_id',
        'field',
        'assignto_by_id',
        'operation',
    ];

     use SoftDeletes;
	 protected $dates = ['deleted_at'];
}
