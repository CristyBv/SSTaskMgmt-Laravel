<?php

namespace App;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'body', 'status', 'deadline', 'priority', 'user_id', 'project_id', 'creator_id'];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function project(){
        return $this->belongsTo('App\Project');
    }

    public function creator(){
        return $this->belongsTo('App\User','creator_id');
    }

    public function history_tasks(){
        return $this->hasMany('App\HistoryTask');
    }

    public function comments(){
        return $this->hasMany('App\TaskComment');
    }
}
