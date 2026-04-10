<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class ReportMapping extends Model
{
    use  Notifiable;
    protected $primaryKey = 'id';

    protected $table = "tb_report_mapping";

    protected $fillable = [
        'report_id',
        'field_lable',
        'field_key',
        'order_by',
        'operator',
        'logic',
        'table_name',
        'value',
        'custom_field_value',
        'type',
    ];

     use SoftDeletes;
	 protected $dates = ['deleted_at'];
}
