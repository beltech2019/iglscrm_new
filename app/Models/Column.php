<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Column extends Model
{
    use  Notifiable;
    protected $primaryKey = 'id';

    protected $table = "tb_column";

    protected $fillable = [
        'column',
        'type',
        'sort_order',
        'is_show',
        'db_field',
    ];
	use SoftDeletes;
	protected $dates = ['deleted_at'];
}
