<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TweetReply extends Model
{
    use HasFactory;
    protected $primaryKey = 'post_id';

    protected $table = "tb_tweet_reply";

    protected $fillable = [
        'replyToTweetId',
        'tweeter_id',
        'tweeter_text',
        'socialUser_id',
        'media_type',
        'url',
        'user_id',
    ];

     use SoftDeletes;
	 protected $dates = ['deleted_at'];
}
