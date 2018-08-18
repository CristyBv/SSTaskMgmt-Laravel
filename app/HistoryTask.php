<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryTask extends Model
{
    protected $fillable = ['task_id', 'user_id', 'forward_by'];
    protected $table = 'history_tasks';

    public function tasks() {
        return $this->belongsTo('App\Task');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function forward() {
        return $this->belongsTo('App\User', 'forward_by');
    }
}
