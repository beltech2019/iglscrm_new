<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TwitterConfigDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tb_twitter_config_details';

    protected $fillable = [
        'email',
        'last_time',
        'count',
        'consumer_key',
        'consumer_secret',
        'access_token',
        'token_secret',
        'user_last_time',
        'bearer_token'
    ];

    protected $dates = ['deleted_at', 'last_time'];
}
