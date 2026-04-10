<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class Reports extends Model
{
    use  Notifiable;
    protected $primaryKey = 'report_id';

    protected $table = "tb_reports";

    protected $fillable = [
        'report_name',
        'assigned_to',
        'created_by',
        'module_name'
    ];

     use SoftDeletes;
	 protected $dates = ['deleted_at'];
}
