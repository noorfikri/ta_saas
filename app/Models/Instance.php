<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instance extends Model
{
    protected $fillable = [
        'name',
        'aws_stack_id',
        'aws_stack_name',
        'app_url',
        'status',
        'message',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
}
