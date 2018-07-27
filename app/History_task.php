<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History_task extends Model
{
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
