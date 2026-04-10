<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProjectAttachment extends Model
{
    use  Notifiable;
    protected $primaryKey = 'id';

    protected $table = "tb_projectattachment";

    protected $fillable = [
        'attachment_id',
        'fileName',
        'filePath',
        'fileUrl',
        'upload_time',
    ];

     use SoftDeletes;
	 protected $dates = ['deleted_at'];
}
