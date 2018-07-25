<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
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
        return $this->hasMany('App\History_task');
    }
}
