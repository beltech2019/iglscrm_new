<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class RulePriority extends Model
{
    use  Notifiable;
    protected $primaryKey = 'id';

    protected $table = "tb_rule_priority";

    protected $fillable = [
        'key',
        'label',
        'type',
        'value'
    ];

     use SoftDeletes;
	 protected $dates = ['deleted_at'];
}
