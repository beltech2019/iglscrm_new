<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $table = "tb_template";

    protected $fillable = [
        'template_name',
        'template_content',
        'template_code',
       

        
    ];
    use SoftDeletes;
	protected $dates = ['deleted_at'];
}

