<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Component extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $table = "tb_component";

    protected $fillable = [
        'component_key',
        'component_label',
        'component_type',
        'component_level'
    ];
    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
