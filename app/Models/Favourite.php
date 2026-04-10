<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class Favourite extends Model
{
    use  Notifiable;
    protected $primaryKey = 'id';

    protected $table = "tb_favourite";

    protected $fillable = [
        'user_id',
        'type',
        'date_created',
        'type_id',
        'status',
    ];

    use SoftDeletes;
	protected $dates = ['deleted_at'];
}
