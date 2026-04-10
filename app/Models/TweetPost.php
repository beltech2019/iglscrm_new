<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TweetPost extends Model
{
    use HasFactory;
    protected $primaryKey = 'post_id';

    protected $table = "tb_tweet_post";

    protected $fillable = [
        'tweeter_id',
        'tweeter_text',
        'socialUser_id',
    ];

     use SoftDeletes;
	 protected $dates = ['deleted_at'];
}
