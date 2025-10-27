<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instance extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'aws_stack_id',
        'aws_stack_name',
        'status',
        'message',
        'app_url',
        'admin_email',
        'admin_password',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
}
