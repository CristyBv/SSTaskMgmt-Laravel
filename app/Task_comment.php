<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task_comment extends Model
{
    protected $fillable = ['title', 'body', 'user_id'];

    public function tasks() {
        return $this->belongsTo('App\Task');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
