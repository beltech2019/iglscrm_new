<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialUser extends Model
{
    use HasFactory;
    protected $primaryKey = 'user_id';

    protected $table = "tb_social_user";

    protected $fillable = [
        'user_id',
        'name',
        'date_modified',
        'user_name'
    ];
    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
