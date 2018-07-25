<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History_task extends Model
{
    public function tasks(){
        return $this->belongsTo('App\Task');
    }
}
