<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    protected $fillable = ['title', 'body', 'user_id', 'task_id'];
    protected $table = 'task_comments';

    public function tasks() {
        return $this->belongsTo('App\Task');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
